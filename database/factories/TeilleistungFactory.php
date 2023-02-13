<?php

namespace Database\Factories;

use App\Models\Leistung;
use App\Models\Note;
use App\Models\Teilleistung;
use App\Models\Teilleistungsart;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeilleistungFactory extends Factory
{
    protected $model = Teilleistung::class;

    public function definition(): array
    {
        return [
            'leistung_id' => Leistung::factory(),
            'teilleistungsart_id' => Teilleistungsart::factory(),
        ];
    }

    public function withDatum(): Factory
    {
        return $this->state(fn (): array => [
			'datum' => now()->format(format: 'Y-m-d'),
		]);
    }

    public function withBemerkung(): Factory
    {
        return $this->state(fn (): array => [
			'bemerkung' => $this->faker->paragraph,
		]);
    }

    public function withNote(): Factory
    {
        return $this->state(fn (): array => [
			'note_id' => Note::factory(),
		]);
    }
}
