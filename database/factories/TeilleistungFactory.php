<?php

namespace Database\Factories;

use App\Models\Leistung;
use App\Models\Note;
use App\Models\Teilleistung;
use App\Models\Teilleistungsart;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Teilleistung>
 */
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
        return $this->state(fn () => ['datum' => now()->format('Y-m-d')]);
    }

    public function withBemerkung(): Factory
    {
        return $this->state(fn () => ['bemerkung' => $this->faker->paragraph]);
    }

    public function withNote(): Factory
    {
        return $this->state(fn () => ['note_id' => Note::factory()]);
    }
}
