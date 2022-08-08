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
            'ext_id' => $this->faker->unique(true)->randomNumber(),
            'schueler_id' => Schueler::factory(),
            'lerngruppe_id' => Lerngruppe::factory(),
            'note_id' => Note::Factory(),
            'abiturfach' => rand(1, 10),
        ];
    }

    public function withIstSchriftlich(): Factory
    {
        return $this->state(fn () => ['istSchriftlich' => true]);
    }

    public function withFehlstundenGesamt(): Factory
    {
        return $this->state(fn () => ['fehlstundenGesamt' => rand(0, 10)]);
    }

    public function withFehlstundenUnentschuldigt(): Factory
    {
        return $this->state(fn () => ['fehlstundenUnentschuldigt' => rand(0, 10)]);
    }

    public function withFachbezogeneBemerkungen(): Factory
    {
        return $this->state(fn () => ['fachbezogeneBemerkungen' => true]);
    }

    public function withNeueZuweisungKursart(): Factory
    {
        return $this->state(fn () => ['neueZuweisungKursart' => $this->faker->word()]);
    }
}
