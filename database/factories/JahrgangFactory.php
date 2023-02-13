<?php

namespace Database\Factories;

use App\Models\Daten;
use App\Models\Jahrgang;
use Illuminate\Database\Eloquent\Factories\Factory;

class JahrgangFactory extends Factory
{
    protected $model = Jahrgang::class;

    public function definition(): array
    {
        return [
            'kuerzel' => $this->faker->word(),
            'kuerzelAnzeige' => $this->faker->word(),
            'beschreibung' => $this->faker->paragraph(),
            'stufe' => $this->faker->word(),
			'sortierung' => rand(min: 1, max: 15)
        ];
    }
}
