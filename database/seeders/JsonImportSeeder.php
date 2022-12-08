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
		$json = File::get("{$this->path}/gesamt-01.json");
		$service = new DataImportService($json);
		$service->import();
	}
}
