<?php

namespace Database\Factories;

use App\Models\Fach;
use App\Models\Schueler;
use App\Models\Sprachenfolge;
use Illuminate\Database\Eloquent\Factories\Factory;

class SprachenfolgeFactory extends Factory
{
    protected $model = Sprachenfolge::class;

    public function definition(): array
    {
        return [
            'schueler_id' => Schueler::factory(),
            'sprache' => $this->faker->unique->word(), 
            'fach_id' => Fach::factory(),
            'reihenfolge' => rand(min: 1, max: 10),
        ];
    }

    public function withBelegungVonJahrgang(): Factory
    {
        return $this->state(fn (): array  => [
			'belegungVonJahrgang' => rand(min: 1, max: 10),
		]);
    }

    public function withBelegungVonAbschnitt(): Factory
    {
        return $this->state(fn (): array  => [
			'belegungVonAbschnitt' => rand(min: 1, max: 10),
		]);
    }

    public function withBelegungBisJahrgang(): Factory
    {
        return $this->state(fn (): array  => [
			'belegungBisJahrgang' => rand(min: 1, max: 10),
		]);
    }

    public function withBelegungBisAbschnitt(): Factory
    {
        return $this->state(fn (): array  => [
			'belegungBisAbschnitt' => rand(min: 1, max: 10),
		]);
    }

    public function withReferenzniveau(): Factory
    {
        return $this->state(fn (): array  => [
			'referenzniveau' => $this->faker->unique->word()
		]);
    }

    public function withBelegungSekI(): Factory
    {
        return $this->state(fn (): array  => [
			'belegungSekI' => rand(max: 3) * 2,
		]);
    }
}
