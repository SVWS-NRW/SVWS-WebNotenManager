<?php

namespace App\Services;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class AesService
{
	/**
	 * @throws Exception
	 */
	public function decrypt(string $data): string
	{
		$decodedData = base64_decode(string: $data, strict: true);

		if (!$decodedData) {
			throw (
				new Exception(
					message: 'Base64 decryption error',
					code: Response::HTTP_BAD_REQUEST,
				)
			);
		}

		$decryptedData = openssl_decrypt(
			data: substr(string: $decodedData, offset: 16),
			cipher_algo: 'aes-256-cbc-hmac-sha256',
			passphrase: $this->key(),
			options: OPENSSL_RAW_DATA|OPENSSL_ZERO_PADDING,
			iv: $this->iv($decodedData)
		);

		if (!$decryptedData) {
			throw (
				new Exception(
					message: 'AES Decryption error',
					code: Response::HTTP_BAD_REQUEST,
				)
			);
		}

		return $decryptedData;
	}

	private function iv(string $string): string
	{
		return substr(string: $string, offset: 0, length: 16);
	}

	private function key(): string
	{
		return hash_pbkdf2(
			algo: 'sha256',
			password: config(key: 'wenom.aes_password'),
			salt: config(key: 'wenom.aes_salt'),
			iterations: 65536,
			length: 32,
			binary: true
		);
	}
}
