<?php

namespace Database\Factories;

use App\Models\Foerderschwerpunkt;
use App\Models\Note;
use App\Models\Schueler;
use Illuminate\Database\Eloquent\Factories\Factory;

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

	private function withTimestamp(
		string $column,
		string|null $tsColumn = null,
		string|bool|int|null $value = null
	): LernabschnittFactory {
		return $this->state(fn (): array => [
			$column => $value ?? $this->faker->paragraph(),
			$tsColumn ?? "ts{$column}" => now()->format(format: 'Y-m-d H:i:s.u'),
		]);
	}
}
