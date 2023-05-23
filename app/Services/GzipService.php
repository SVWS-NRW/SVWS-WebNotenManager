<?php

namespace App\Services;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class GzipService
{
	/**
	 * @throws Exception
	 */
	public function encode(string $file): string
	{
		try {
			return gzencode(data: $file, level: 9);
		} catch (Exception $e) {
			throw(
				new Exception(
					message: "File encoding failed: {$e->getMessage()}",
					code: Response::HTTP_BAD_REQUEST,
				)
			);
		}
	}

	/**
	 * @throws Exception
	 */
	public function decode(string $file): string
	{
		try {
			return gzdecode(data: $file);
		} catch (Exception $e) {
			throw(
				new Exception(
					message: "File decoding failed: {$e->getMessage()}",
					code: Response::HTTP_BAD_REQUEST,
				)
			);
		}
	}
}
