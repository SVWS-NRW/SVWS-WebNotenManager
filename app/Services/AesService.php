<?php

namespace App\Services;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class AesService
{
	const ENCRYPT_CIPHER = 'aes-256-cbc';
	const DECRYPT_CIPHER = 'aes-256-cbc-hmac-sha256';
	const OPTIONS = OPENSSL_NO_PADDING;

	/**
	 * @throws Exception
	 */
	public function encrypt($data): string
	{
		$initializationVector = $this->generateInitializationVector();

		$encryptedData = openssl_encrypt(
			data: $this->pad(plainText: $data),
			cipher_algo: self::ENCRYPT_CIPHER,
			passphrase: $this->getPassphrase(),
			options: self::OPTIONS,
			iv: $initializationVector,
		);

		if ($encryptedData === false) {
			throw (
				new Exception(
					message: "AES Encryption error: {openssl_error_string()}",
					code: Response::HTTP_BAD_REQUEST,
				)
			);
		}

		return $this->prependInitializationVector(
			string: base64_encode(string: $encryptedData),
			initializationVector: $initializationVector,
		);
	}

	/**
	 * @throws Exception
	 */
	public function decrypt(string $data): string
	{
		$decodedData = base64_decode(string: $data, strict: true);

		if ($decodedData === false) {
			throw (
				new Exception(
					message: 'Base64 decryption error',
					code: Response::HTTP_BAD_REQUEST,
				)
			);
		}

		$decryptedData = openssl_decrypt(
			data: $this->dataWithOutInitializationVector(data: $decodedData),
			cipher_algo: self::DECRYPT_CIPHER,
			passphrase: $this->getPassphrase(),
			options: self::OPTIONS,
			iv: $this->extractInitializationVector(string: $decodedData)
		);

		if ($decryptedData === false) {
			throw (
				new Exception(
					message: 'AES Decryption error',
					code: Response::HTTP_BAD_REQUEST,
				)
			);
		}

		return $decryptedData;
	}

	private function getPassphrase(): string
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

	private function generateInitializationVector(): string
	{
		return openssl_random_pseudo_bytes(length: openssl_cipher_iv_length(cipher_algo: self::ENCRYPT_CIPHER));
	}

	private function extractInitializationVector(string $string): string
	{
		return substr(string: $string, offset: 0, length: 16);
	}

	private function prependInitializationVector(string $string, string $initializationVector): string
	{
		return substr_replace(string: $string, replace: $initializationVector, offset: 0, length: 0);
	}

	private function dataWithOutInitializationVector(string $data): string
	{
		return substr(string: $data, offset: 16);
	}

	private function pad(string $plainText): string
	{
		if (strlen(string: $plainText) % 16 == 0) {
			return $plainText;
		}

		return str_pad(
			string: $plainText,
			length: strlen(string: $plainText) + 16 - strlen(string: $plainText) % 16,
			pad_string: "\0",
		);
	}
}
