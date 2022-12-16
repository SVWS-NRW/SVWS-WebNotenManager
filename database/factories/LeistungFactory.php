<?php

namespace Database\Factories;

use App\Models\Kurs;
use App\Models\Leistung;
use App\Models\Lerngruppe;
use App\Models\Note;
use App\Models\Schueler;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Leistung>
 */
class LeistungFactory extends Factory
{
    protected $model = Leistung::class;

    public function definition(): array
    {
        return [
            'schueler_id' => Schueler::factory(),
            'lerngruppe_id' => Lerngruppe::factory(),
        ];
    }

    public function withAbiturfach(int|null $amount = null): LeistungFactory
    {
        return $this->state(fn () => ['abiturfach' => $amount ?? rand(0, 10)]);
    }

    public function withNote(): LeistungFactory
    {
		return $this->withTimestamp(column: 'note_id', tsColumn: 'tsNote', value: Note::Factory());
    }

    public function istSchriftlich(): LeistungFactory
    {
        return $this->state(fn () => ['istSchriftlich' => true]);
    }

    public function withFehlstundenGesamt(int|null $amount = null): LeistungFactory
    {
		return $this->withTimestamp(column: 'fehlstundenGesamt', value: $amount ?? rand(0, 10));
    }

    public function withFehlstundenUnentschuldigt(int|null $amount = null): LeistungFactory
    {
		return $this->withTimestamp(column: 'fehlstundenUnentschuldigt', value: $amount ?? rand(0, 10));
    }

    public function withFachbezogeneBemerkungen(): LeistungFactory
    {
		return $this->withTimestamp(column: 'fachbezogeneBemerkungen');
    }

	public function istGemahnt(): LeistungFactory
	{
		return $this->withTimestamp(column: 'istGemahnt', value: true);
	}

	public function withNeueZuweisungKursart(): LeistungFactory
	{
		return $this->state(fn () => ['neueZuweisungKursart' => $this->faker->word()]);
	}

	private function withTimestamp(
		string $column,
		string|null $tsColumn = null,
		string|bool|int|NoteFactory|null $value = null
	): LeistungFactory {
		return $this->state(fn () => [
			$column => $value ?? $this->faker->paragraph(),
			$tsColumn ?? "ts{$column}" => now()->format('Y-m-d H:i:s.u'),
		]);
	}
}
