<?php

namespace Database\Factories;

use App\Models\Jahrgang;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Jahrgang>
 */
class JahrgangFactory extends Factory
{
    protected $model = Jahrgang::class;

    public function definition(): array
    {
        return [
            'kuerzel' => $this->faker->unique->word(),
            'kuerzelAnzeige' => $this->faker->unique->word(),
            'beschreibung' => $this->faker->paragraph(),
            'stufe' => $this->faker->unique->word(),
        ];
    }

    public function withSortierung(): Factory
    {
        return $this->state(fn () => ['sortierung' => rand(1, 15)]);
    }
}