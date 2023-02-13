<?php

namespace Database\Factories;

use App\Models\Daten;
use App\Models\Foerderschwerpunkt;
use Illuminate\Database\Eloquent\Factories\Factory;

class FoerderschwerpunktFactory extends Factory
{
    protected $model = Foerderschwerpunkt::class;

    public function definition(): array
    {
        return [
            'kuerzel' => $this->faker->unique->word(),
            'beschreibung' => $this->faker->paragraph(),
        ];
    }
}
