<?php

namespace Database\Factories;

use App\Models\Fach;
use App\Models\Floskel;
use App\Models\Floskelgruppe;
use App\Models\Jahrgang;
use Illuminate\Database\Eloquent\Factories\Factory;

class FloskelFactory extends Factory
{
    protected $model = Floskel::class;

    public function definition(): array
    {
        return [
            'floskelgruppe_id' => Floskelgruppe::class,
            'kuerzel' => $this->faker->word(),
            'text' => $this->faker->paragraph(),
        ];
    }

    public function fach(): Factory
    {
        return $this->state(fn (): array  => [
			'fach_id' => Fach::factory(),
		]);
    }

    public function niveau(): Factory
    {
        return $this->state(fn (): array  => [
			'niveau' => rand(min: 1, max: 10),
		]);
    }

    public function jahrgang(): Factory
    {
        return $this->state(fn (): array  => [
			'jahrgang_id' => Jahrgang::factory(),
		]);
    }
}

