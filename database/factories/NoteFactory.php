<?php

namespace Database\Factories;

use App\Models\Daten;
use App\Models\Note;
use Illuminate\Database\Eloquent\Factories\Factory;

class NoteFactory extends Factory
{
    protected $model = Note::class;

    public function definition(): array
    {
        return [
            'kuerzel' => $this->faker->word(),
            'text' => $this->faker->paragraph(),
        ];
    }

    public function withNotenpunkte(): Factory
    {
        return $this->state(fn (): array  => [
			'notenpunkte' => rand(min: 1, max: 15),
		]);
    }
}
