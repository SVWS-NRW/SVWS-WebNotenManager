<?php

namespace App\Services;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class GzipService
{
	/**
	 * @throws Exception
	 */
	public function encode(string $data): string
	{
        $encodedData = @gzencode($data, 9);

        if ($encodedData === false) {
            throw(new Exception(error_get_last()));
        }

        return $encodedData;
	}

	/**
	 * @throws Exception
	 */
	public function decode(string $data): string
	{
        $decodedData = @gzdecode($data);

        if ($decodedData === false) {
            throw(new Exception(error_get_last()));
        }

        return $decodedData;
	}
}
