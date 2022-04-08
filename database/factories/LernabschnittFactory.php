<?php

namespace Database\Factories;

use App\Models\Foerderschwerpunkt;
use App\Models\Note;
use App\Models\Schueler;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lernabschnitt>
 */
class LernabschnittFactory extends Factory
{
    public function definition(): array
    {
        return [
            'schueler_id' => Schueler::factory(),
            'pruefungsordnung' => $this->faker->unique->word(),
            'lernbereich1note' => Note::factory(),
            'lernbereich2note' => Note::factory(),
            'foerderschwerpunkt1' => Foerderschwerpunkt::factory(),
            'foerderschwerpunkt2' => Foerderschwerpunkt::factory(),
        ];
    }
}
