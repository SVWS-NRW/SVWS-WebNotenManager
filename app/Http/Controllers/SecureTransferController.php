<?php

namespace App\Http\Controllers;

use App\Services\AesService;
use App\Services\GzipService;
use App\Services\DataImportService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as Status;

class SecureTransferController extends Controller
{
    public function import(
		Request           $request,
		GzipService       $gzipService,
		AesService        $aesService,
		DataImportService $importService,
	): Response {
		if ($request->missing(key: 'file')) {
			return response(content: 'File not found.', status: Status::HTTP_UNPROCESSABLE_ENTITY);
		}

		try {
			$encodedData = $gzipService->decompress(file: $request->file(key: 'file')->getContent());
		} catch (Exception $e) {
			return response(content: $e->getMessage(), status: Status::HTTP_BAD_REQUEST);
		}

		try {
			$decryptedData = $aesService->decrypt(data: $encodedData);
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

    public function export(): Response
	{


		return response(content: 'Export successful', status: Status::HTTP_OK);
	}
}
