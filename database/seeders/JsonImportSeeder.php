<?php

namespace Database\Seeders;

use App\Services\DataImportService;
use File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JsonImportSeeder extends Seeder
{
    private int $seedFile = 2;

    private array $files = [
        0 => 'Grundschule_2020_2_Klassenlehrer_alle Fächer.json',
        1 => 'GymOberstufe_Klassenlehrer_EF.json',
        2 => 'Gesamtschule_Klassenlehrer_05a.json',
    ];

    public function run(): void
    {
        $file = $this->files[array_key_exists($this->seedFile, $this->files) ? $this->seedFile : 0];
        $json = File::get("database/seeders/data/{$file}");
        
        $service = new DataImportService($json);
        $service->import();
    }
}
