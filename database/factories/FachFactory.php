<?php

namespace Database\Factories;

use App\Models\Fach;
use Illuminate\Database\Eloquent\Factories\Factory;

class FachFactory extends Factory
{
    protected $model = Fach::class;

    public function definition(): array
    {
        return [
            'kuerzel' => $this->faker->unique->word(),
            'kuerzelAnzeige' => $this->faker->unique->word(),
            'sortierung' => rand(min: 1, max: 15),
            'istFremdsprache' => $this->faker->boolean(),
        ];
    }
}