<?php

namespace Database\Factories;

use App\Models\Daten;
use App\Models\Fach;
use App\Models\Klasse;
use App\Models\Kurs;
use App\Models\Lerngruppe;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lerngruppe>
 */
class LerngruppeFactory extends Factory
{
    protected $model = Lerngruppe::class;

    public function definition(): array
    {
        return [
            'fach_id' => Fach::factory(),
            'klasse_id' => Klasse::factory(),
			'kID' => $this->faker->numberBetween(1, 1_000_000),
            'kursartID' => $this->faker->numberBetween(1, 1_000_000),
            'bezeichnung' => $this->faker->unique->word(),
            'kursartKuerzel' => $this->faker->unique->word(),
			'bilingualeSprache' => $this->faker->unique->word(),
            'wochenstunden' => rand(1, 10),
        ];
    }


}
