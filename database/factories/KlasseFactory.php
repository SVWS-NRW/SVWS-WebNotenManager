<?php

namespace Database\Factories;

use App\Models\Klasse;
use Illuminate\Database\Eloquent\Factories\Factory;

class KlasseFactory extends Factory
{
    protected $model = Klasse::class;

    public function definition(): array
    {
        return [
            'kuerzel' => $this->faker->unique->word(),
            'kuerzelAnzeige' => $this->faker->unique->word(),
			'sortierung' => rand(min: 1, max: 15)
        ];
    }
}