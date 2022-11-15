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
        $groupable = $this->groupable();

        return [
            'groupable_id' => $groupable::factory(),
            'groupable_type' => $groupable,
            'fach_id' => Fach::factory(),
            'kursartID' => $this->faker->unique->word(),
            'bezeichnung' => $this->faker->unique->word(),
            'kursartKuerzel' => $this->faker->unique->word(),
			'bilingualeSprache' => $this->faker->unique->word(),
            'wochenstunden' => rand(1, 10),
        ];
    }

    public function groupable(): string
    {
        return $this->faker->randomElement([Klasse::class, Kurs::class]);
    }
}
