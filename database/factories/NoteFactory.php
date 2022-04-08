<?php

namespace Database\Factories;

use App\Models\Note;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Note>
 */
class NoteFactory extends Factory
{    
    protected $model = Note::class;

    public function definition(): array
    {
        return [
            'kuerzel'=> $this->faker->unique->word(),
            'text' => $this->faker->paragraph(),
        ];
    }

    public function withNotenpunkte(): Factory
    {
        return $this->state(fn () => ['notenpunkte' => rand(1, 15)]);
    }
}
