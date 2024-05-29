<?php

namespace Database\Factories;

use App\Models\Jahrgang;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory for creating Jahrgang model instances.
 *
 * @package Database\Factories
 */
class JahrgangFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Jahrgang::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'kuerzel' => $this->faker->word(),
            'kuerzelAnzeige' => $this->faker->word(),
            'beschreibung' => $this->faker->catchPhrase(),
            'stufe' => $this->faker->word(),
            'sortierung' => rand(min: 1, max: 15)
        ];
    }
}
