<?php

namespace Database\Seeders;

use App\Services\DataImportService;
use File;
use Illuminate\Database\Seeder;

class JsonImportSeeder extends Seeder
{
	private string $path = 'database/seeders/data/single';

	public function run(): void
	{
		collect($this->getFiles())->each(fn (string $file) => $this->seed($file));
	}

	private function getFiles(): array
	{
		return array_diff(scandir($this->path), ['.', '..']);
	}

	private function seed(string $file): void
	{
		$json = File::get("{$this->path}/{$file}");
		$service = new DataImportService($json);
		$service->import();
	}
}
