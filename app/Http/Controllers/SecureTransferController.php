<?php

namespace App\Http\Controllers;

use App\Http\Resources\Export\SchuelerResource;
use App\Models\Schueler;
use App\Services\AesService;
use App\Services\EnvService;
use App\Services\DataImportService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as Status;

class SecureTransferController extends Controller
{
    public function import(
        Request           $request,
        EnvService        $gzipService,
        AesService        $aesService,
        DataImportService $importService,
	): Response {
		if ($request->missing(key: 'file')) {
			return response(content: 'File not found.', status: Status::HTTP_UNPROCESSABLE_ENTITY);
		}

		try {
			$decodedData = $gzipService->decode(file: $request->file(key: 'file')->getContent());
		} catch (Exception $e) {
			return response(content: $e->getMessage(), status: Status::HTTP_BAD_REQUEST);
		}

		try {
			$decryptedData = $aesService->decrypt(data: $decodedData);
		} catch (Exception $e) {
			return response(content: $e->getMessage(), status: Status::HTTP_BAD_REQUEST);
		}

		$json = json_decode(json: $decryptedData, associative: true);

		if ($json['schulnummer'] != config(key: 'wenom.schulnummer')) {
			return response(content: 'Invalid school number', status: Status::HTTP_BAD_REQUEST);
		}

		$importService->execute(data: $json);

		return response(content: 'Import successful', status: Status::HTTP_OK);
	}

    public function export(AesService $aesService, EnvService $gzipService): Response
	{
		$data = SchuelerResource::collection(
			resource: Schueler::exportCollection()
		)->toJson();

		try {
			$encryptedData = $aesService->encrypt(data: $data);
		} catch (Exception $e) {
			return response(content: $e->getMessage(), status: Status::HTTP_BAD_REQUEST);
		}

		try {
			$encodedData = $gzipService->encode(file: $encryptedData);
		} catch (Exception $e) {
			return response(content: $e->getMessage(), status: Status::HTTP_BAD_REQUEST);
		}

		return response(content: $encodedData, status: Status::HTTP_OK);
	}
}
