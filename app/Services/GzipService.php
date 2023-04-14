<?php

namespace App\Services;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class GzipService
{
	/**
	 * @throws Exception
	 */
	public function decompress(string $file): string
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
