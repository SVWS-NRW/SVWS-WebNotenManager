<?php

namespace Database\Factories;

use App\Models\Foerderschwerpunkt;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Foerderschwerpunkt>
 */
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
