<?php

namespace Database\Factories;

use App\Models\Fach;
use App\Models\Schueler;
use App\Models\Sprachenfolge;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sprachenfolge>
 */
class SprachenfolgeFactory extends Factory
{
    protected $model = Sprachenfolge::class;

    public function definition(): array
    {
        return [
            'schueler_id' => Schueler::factory(),
            'sprache' => $this->faker->unique->word(), 
            'fach_id' => Fach::factory(),
            'reihenfolge' => rand(1, 10),
        ];
    }

    public function withBelegungVonJahrgang(): Factory
    {
        return $this->state(fn () => ['belegungVonJahrgang' => rand(1, 10)]);
    }

    public function withBelegungVonAbschnitt(): Factory
    {
        return $this->state(fn () => ['belegungVonAbschnitt' => rand(1, 10)]);
    }

    public function withBelegungBisJahrgang(): Factory
    {
        return $this->state(fn () => ['belegungBisJahrgang' => rand(1, 10)]);
    }

    public function withBelegungBisAbschnitt(): Factory
    {
        return $this->state(fn () => ['belegungBisAbschnitt' => rand(1, 10)]);
    }

    public function withReferenzniveau(): Factory
    {
        return $this->state(fn () => ['referenzniveau' => $this->faker->unique->word()]);
    }

    public function withBelegungSekI(): Factory
    {
        return $this->state(fn () => ['belegungSekI' => rand(0, 3) * 2]);
    }
}
