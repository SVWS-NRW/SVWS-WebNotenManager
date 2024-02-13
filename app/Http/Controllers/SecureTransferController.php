<?php

namespace App\Http\Controllers;

use App\Http\Requests\SecureImportRequest;
use App\Http\Resources\Export\SchuelerResource;
use App\Models\Schueler;
use App\Services\{DataImportService, GzipService};
use Exception;
use Illuminate\Http\{JsonResponse, Response};
use Symfony\Component\HttpFoundation\Response as Status;

/**
 * Controller for secure data transfer operations.
 */
class SecureTransferController extends Controller
{
    /**
     *  Method for a basic check, probably for health or connectivity.
     *
     * @return JsonResponse
     */
    public function check(): JsonResponse
    {
        return response()->json(['message' => 'Erfolg.']);
    }

    /**
     * Method for importing data securely.
     *
     * @param SecureImportRequest $request
     * @param DataImportService $importService
     * @param GzipService $gzipService
     * @return JsonResponse|Response
     */
    public function import(
		SecureImportRequest $request,
		DataImportService $importService,
        GzipService $gzipService,
	): Response|JsonResponse {
        // Retrieving the uploaded file from the request.
        $file = $request->file('file');

        // Attempt to decompress the file content using GZIP.
        try {
			$decodedData = $gzipService->decode($file->getContent());
		} catch (Exception $e) {
			return response()->json([
                'message' => 'Ein Fehler ist beim Dekomprimieren der Daten aufgetreten: '. $e->getMessage(),
            ], Status::HTTP_BAD_REQUEST);
		}

        // Decoding the decrypted data from JSON.
        $json = json_decode($decodedData, true);

        // Check for JSON decoding errors.
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json([
                'message' => 'Ein Fehler ist beim JSON-Dekodierung aufgetreten: '. json_last_error_msg(),
            ], Status::HTTP_BAD_REQUEST);
        }

        // Validating the 'schulnummer' from the decoded JSON.
		if ($json['schulnummer'] != config('wenom.schulnummer')) {
			return response()->json(['message' => 'Schulnummer nicht gültig'], Status::HTTP_BAD_REQUEST);
		}

        // Executing the import service with the validated data.
		$importService->execute($json);

        // Returning a successful response.
		return response();
	}

    /**
     * Method for exporting data securely.
     *
     * @param GzipService $gzipService
     * @return Response
     */
    public function export(GzipService $gzipService): Response
	{
        // Fetching data to be exported and converting it to JSON.
        $data = SchuelerResource::collection(Schueler::exportCollection())->toJson();

        // Attempt to GZIP encode the encrypted data.
        try {
            return response($gzipService->encode($data));
		} catch (Exception $e) {
			return response([
                'message' => "Ein Fehler ist beim Komprimieren der Daten aufgetreten: {$e->getMessage()}",
            ], Status::HTTP_INTERNAL_SERVER_ERROR);
		}
	}
}
