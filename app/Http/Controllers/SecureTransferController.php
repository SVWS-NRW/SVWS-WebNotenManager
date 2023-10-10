<?php

namespace App\Http\Controllers;

use App\Http\Requests\SecureImportRequest;
use App\Http\Resources\Export\SchuelerResource;
use App\Models\Schueler;
use App\Services\AesService;
use App\Services\DataImportService;
use App\Services\GzipService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as Status;

class SecureTransferController extends Controller
{
    public function check(): JsonResponse
    {
        return response()->json(['message' => 'Erfolg.']);
    }

    public function import(
		SecureImportRequest $request,
		AesService $aesService,
		DataImportService $importService,
        GzipService $gzipService,
	): JsonResponse {
        $file = $request->file('file');

		try {
			$decodedData = $gzipService->decode($file->getContent());
		} catch (Exception $e) {
			return response()->json([
                'message' => 'Ein Fehler ist beim Dekomprimieren der Daten aufgetreten: '. $e->getMessage(),
            ], Status::HTTP_BAD_REQUEST);
		}

		try {
			$decryptedData = $aesService->decrypt($decodedData);
		} catch (Exception $e) {
            return response()->json([
                'message' => 'Ein Fehler ist beim Base64 Entschlüsseln aufgetreten: '. $e->getMessage(),
            ], Status::HTTP_BAD_REQUEST);
		}

        $json = json_decode($decryptedData, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json([
                'message' => 'Ein Fehler ist beim JSON-Dekodierung aufgetreten: '. json_last_error_msg(),
            ], Status::HTTP_BAD_REQUEST);
        }

		if ($json['schulnummer'] != config('wenom.schulnummer')) {
			return response()->json(['message' => 'Schulnummer nicht gültig'], Status::HTTP_BAD_REQUEST);
		}

		$importService->execute($json);

		return response()->json();
	}

    public function export(AesService $aesService, GzipService $gzipService): Response
	{
		$data = SchuelerResource::collection(Schueler::exportCollection())->toJson();

		try {
			$encryptedData = $aesService->encrypt($data);
		} catch (Exception $e) {
			return response([
                'message' => 'Ein Fehler ist beim AES-CBC Verschlüsseln aufgetreten: ' .$e->getMessage(),
            ], Status::HTTP_INTERNAL_SERVER_ERROR);
		}

		try {
            $encodedData = $gzipService->encode($encryptedData);
		} catch (Exception $e) {
			return response([
                'message' => 'Ein Fehler ist beim Komprimieren der Daten aufgetreten: '. $e->getMessage(),
            ], Status::HTTP_INTERNAL_SERVER_ERROR);
		}

		return response($encodedData, Status::HTTP_OK);
	}
}
