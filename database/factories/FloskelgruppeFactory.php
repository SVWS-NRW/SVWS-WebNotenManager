<?php

namespace Database\Factories;

use App\Models\Floskelgruppe;
use Illuminate\Database\Eloquent\Factories\Factory;

class FloskelgruppeFactory extends Factory
{
    protected $model = Floskelgruppe::class;

    public function definition(): array
    {
        return [
            'kuerzel' => $this->faker->unique->word(),
            'bezeichnung' => $this->faker->catchPhrase(),
            'hauptgruppe' => $this->faker->unique->word(),
        ];
    }
}