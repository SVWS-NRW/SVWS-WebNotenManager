<?php

namespace Database\Factories;

use App\Models\Daten;
use App\Models\Fach;
use App\Models\Klasse;
use App\Models\Kurs;
use App\Models\Lerngruppe;
use Illuminate\Database\Eloquent\Factories\Factory;

class LerngruppeFactory extends Factory
{
    protected $model = Lerngruppe::class;

    public function definition(): array
    {
        return [
            'fach_id' => Fach::factory(),
            'klasse_id' => Klasse::factory(),
			'kID' => $this->faker->numberBetween(int1: 1, int2: 1_000_000),
            'kursartID' => $this->faker->numberBetween(int1: 1, int2: 1_000_000),
            'bezeichnung' => $this->faker->unique->word(),
            'kursartKuerzel' => $this->faker->unique->word(),
			'bilingualeSprache' => $this->faker->unique->word(),
            'wochenstunden' => rand(min: 1, max: 10),
        ];
    }


}
