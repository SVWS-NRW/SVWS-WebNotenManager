<?php

namespace Database\Factories;

use App\Models\Daten;
use App\Models\Note;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory for creating Note model instances.
 *
 * @package Database\Factories
 */
class NoteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Note::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'kuerzel' => $this->faker->unique(reset: false)->word(),
            'text' => $this->faker->word(),
        ];
    }

    /**
     * Indicate that the model has Notenpunkte.
     *
     * @return NoteFactory
     */
    public function withNotenpunkte(): NoteFactory
    {
        return $this->state(fn (): array  => [
			'notenpunkte' => rand(1, 15),
		]);
    }
}
