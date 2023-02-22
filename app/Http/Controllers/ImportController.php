<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\DataImportService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class ImportController extends Controller
{
    public function curl() {
		$endpoint = 'https://nightly.svws-nrw.de/db/ENM1/enm/alle';

		$response = Http::accept(contentType: 'application/json')
			->withBasicAuth(username: 'Admin', password: '')
			->get(url: $endpoint);

		$this->import(
			data: json_decode(
				json: $response->body(),
				associative: true
			)
		);
	}

    public function request(): void
    {
		$keys = [
			'lehrer',
			'foerderschwerpunkte',
			'klassen',
			'noten',
			'jahrgaenge',
			'faecher',
			'floskelgruppen',
			'lerngruppen',
			'teilleistungsarten',
			'schueler'
		];

		$this->import(data: request()->only(keys: $keys));
    }

	private function import(array $data): void
	{
		$service = new DataImportService(data: $data);
		$service->import();

		User::all()->each(callback: fn (User $user): bool => $user->update(
			attributes: ['password' => Hash::make(value: 'password')])
		);
	}
}
