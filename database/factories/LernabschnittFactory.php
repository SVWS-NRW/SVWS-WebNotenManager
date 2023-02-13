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

	public function withFehlstundenGesamt(int|null $amount = null): LernabschnittFactory
	{
		return $this->withTimestamp(column: 'fehlstundenGesamt', value: $amount ?? rand(max: 10));
	}

	public function withFehlstundenUnentschuldigt(int|null $amount = null): LernabschnittFactory
	{
		return $this->withTimestamp(column: 'fehlstundenUnentschuldigt', value: $amount ?? rand(max: 10));
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
