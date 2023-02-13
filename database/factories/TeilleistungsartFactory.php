<?php

namespace Database\Factories;

use App\Models\Teilleistungsart;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeilleistungsartFactory extends Factory
{
    protected $model = Teilleistungsart::class;

    public function definition(): array
    {
        return [
            'bezeichnung' => $this->faker->unique->word(),
        ];
    }

    public function withSortierung(): Factory
    {
        return $this->state(fn (): array => [
			'sortierung' => rand(min: 1, max: 15),
		]);
    }

    public function withGewichtung(): Factory
    {
        return $this->state(fn (): array => [
			'gewichtung' => (float) rand(min: 1, max: 100) / 100],
		);
    }
}