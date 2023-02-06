<?php

namespace Database\Seeders;

use App\Services\DataImportService;
use File;
use Illuminate\Database\Seeder;

class JsonImportSeeder extends Seeder
{
	private string $path = 'database/seeders/data';

	public function run(): void
	{
		$json = File::get(path: "{$this->path}/curl.json");

		$service = new DataImportService(
			data: json_decode(json: $json, associative: true)
		);

		$service->import();
	}
}
