<?php

namespace Database\Factories;

use App\Models\Fach;
use App\Models\Floskel;
use App\Models\Floskelgruppe;
use App\Models\Jahrgang;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Floskel>
 */
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

    public function withFach(): Factory
    {
        return $this->state(fn () => ['fach_id' => Fach::factory()]);
    }

    public function withNiveau(): Factory
    {
        return $this->state(fn () => ['niveau' => rand(1, 10)]);
    }

    public function withJahrgang(): Factory
    {
        return $this->state(fn () => ['jahrgang_id' => Jahrgang::factory()]);
    }
}

