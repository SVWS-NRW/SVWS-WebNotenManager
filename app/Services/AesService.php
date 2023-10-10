<?php

namespace App\Services;

use Exception;

class AesService
{
	private string $encryptCipher = 'aes-256-cbc';
    private string $decryptCipher = 'aes-256-cbc-hmac-sha256';
	private int $options = OPENSSL_NO_PADDING;

	/**
	 * @throws Exception
	 */
	public function encrypt($data): string
	{
        if (!in_array($this->encryptCipher, openssl_get_cipher_methods())) {
            throw (new Exception($this->encryptCipher .' wird von Ihrer OpenSSL-Version unterstützt.'));
        }

		$initializationVector = $this->generateInitializationVector();

		$encryptedData = openssl_encrypt(
			data: $this->pad($data),
			cipher_algo: $this->encryptCipher,
			passphrase: $this->getPassphrase(),
			options: $this->options,
			iv: $initializationVector,
		);

		if ($encryptedData === false) {
			throw (new Exception(error_get_last()));
		}

		return $this->prependInitializationVector(base64_encode($encryptedData), $initializationVector);
	}

	/**
	 * @throws Exception
	 */
	public function decrypt(string $data): string
	{
        if (!in_array($this->decryptCipher, openssl_get_cipher_methods())) {
            throw (new Exception($this->decryptCipher .' wird von Ihrer OpenSSL-Version unterstützt.'));
        }

		$decodedData = base64_decode($data, true);

		if ($decodedData === false) {
			throw (new Exception(error_get_last()));
		}

		$decryptedData = openssl_decrypt(
			data: $this->dataWithOutInitializationVector($decodedData),
			cipher_algo: $this->decryptCipher,
			passphrase: $this->getPassphrase(),
			options: $this->options,
			iv: $this->extractInitializationVector($decodedData)
		);

		if ($decryptedData === false) {
			throw (new Exception('Ein Fehler ist beim AES-CBC Entschlüsseln aufgetreten: '. error_get_last()));
		}

		return $decryptedData;
	}

	private function getPassphrase(): string
	{
		return hash_pbkdf2(
			algo: 'sha256',
			password: config('wenom.aes_password'),
			salt: config('wenom.aes_salt'),
			iterations: 65536,
			length: 32,
			binary: true
		);
	}

	private function generateInitializationVector(): string
	{
		return openssl_random_pseudo_bytes(openssl_cipher_iv_length($this->encryptCipher));
	}

	private function extractInitializationVector(string $string): string
	{
		return substr($string, 0, 16);
	}

	private function prependInitializationVector(string $string, string $initializationVector): string
	{
		return substr_replace($string, $initializationVector, 0, 0);
	}

	private function dataWithOutInitializationVector(string $data): string
	{
		return substr($data, 16);
	}

	private function pad(string $plainText): string
	{
		if (strlen($plainText) % 16 == 0) {
			return $plainText;
		}

		return str_pad($plainText, strlen($plainText) + 16 - strlen($plainText) % 16, "\0");
	}
}
