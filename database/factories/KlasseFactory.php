<?php

namespace Database\Factories;

use App\Models\Klasse;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Klasse>
 */
class KlasseFactory extends Factory
{
    protected $model = Klasse::class;

    public function definition(): array
    {
        return [
            'kuerzel' => $this->faker->unique->word(),
            'kuerzelAnzeige' => $this->faker->unique->word(),
			'sortierung' => rand(1, 15)
        ];
    }
}