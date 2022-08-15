<?php

namespace Database\Factories;

use App\Models\Floskelgruppe;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Floskelgruppe>
 */
class FloskelgruppeFactory extends Factory
{
    protected $model = Floskelgruppe::class;

    public function definition(): array
    {
        return [
            'kuerzelAnzeige' => $this->faker->unique->word(),
            'bezeichnung' => $this->faker->paragraph,
            'hauptgruppe' => $this->faker->unique->word(),
        ];
    }
}