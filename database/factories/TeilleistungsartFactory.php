<?php

namespace Database\Factories;

use App\Models\Teilleistungsart;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Teilleistungsart>
 */
class TeilleistungsartFactory extends Factory
{
    protected $model = Teilleistungsart::class;

    public function definition()
    {
        return [
            'ext_id' => $this->faker->unique(true)->randomNumber(),
            'bezeichnung' => $this->faker->unique->word(),
        ];
    }

    public function withSortierung(): Factory
    {
        return $this->state(fn () => ['sortierung' => rand(1, 15)]);
    }

    public function withGewichtung(): Factory
    {
        return $this->state(fn () => ['gewichtung' => (float) rand(1, 100) / 100]);
    }
}