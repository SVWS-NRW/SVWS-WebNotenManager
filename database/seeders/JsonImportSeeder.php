<?php

namespace Database\Seeders;

use App\Services\DataImportService;
use File;
use Illuminate\Database\Seeder;

class JsonImportSeeder extends Seeder
{
	private int $seedFile = 0;

	private array $files = [
		'ID22-DORI.json',
		'ID27-BECK.json',
		'ID29-MEYB.json',
		'ID130-FARI.json',
		'ID143-BERG.json',
		'ID211-PFIR.json',
		'ID212-DABR.json',
		'ID219-BAUM.json',
		'ID234-HORK.json',
	];

	public function run(): void
	{
		if ($this->seedFile == -1) {
			collect($this->files)->each(fn (string $file) => $this->seed($file));
			return;
		}

		$file = $this->files[array_key_exists($this->seedFile, $this->files) ? $this->seedFile : 0];
		$this->seed($file);
	}

	private function seed(string $file): void
	{
		$json = File::get("database/seeders/data/{$file}");

		$service = new DataImportService($json);
		$service->import();
	}
}
