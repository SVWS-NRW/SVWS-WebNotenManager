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
		$json = json_decode(File::get("{$this->path}/gesamt-01.json"), true);

		$service = new DataImportService(
			lehrer: $json['lehrer'],
			foerderschwerpunkte: $json['foerderschwerpunkte'],
			klassen: $json['klassen'],
			noten: $json['noten'],
			jahrgaenge: $json['jahrgaenge'],
			faecher: $json['faecher'],
			floskelgruppen: $json['floskelgruppen'],
			lerngruppen: $json['lerngruppen'],
			teilleistungsarten: $json['teilleistungsarten'],
			schueler: $json['schueler'],
		);

		$service->import();
	}
}
