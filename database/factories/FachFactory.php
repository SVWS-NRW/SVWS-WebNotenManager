<?php

namespace Database\Factories;

use App\Models\Fach;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Fach>
 */
class FachFactory extends Factory
{
    protected $model = Fach::class;

    public function definition(): array
    {
        return [
            'ext_id' => $this->faker->unique(true)->randomNumber(),
            'kuerzel' => $this->faker->unique->word(),
            'kuerzelAnzeige' => $this->faker->unique->word(),
            'sortierung' => rand(1, 15),
            'istFremdsprache' => $this->faker->boolean(),
        ];
    }
}