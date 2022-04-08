<?php

namespace Database\Factories;

use App\Models\Jahrgang;
use App\Models\Klasse;
use App\Models\Schueler;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Schueler>
 */
class SchuelerFactory extends Factory
{
    protected $model = Schueler::class;

    public function definition(): array
    {
        return [
            'jahrgang_id' => Jahrgang::factory(),
            'klasse_id' => Klasse::factory(),
            'nachname' => $this->faker->lastName(),
            'vorname' => $this->faker->firstName(),
        ];
    }

    public function withBilingualeSprache(): Factory
    {
        return $this->state(fn () => ['bilingualeSprache' => $this->faker->unique->word()]);
    }

    public function withIstZieldifferent(): Factory
    {
        return $this->state(fn () => ['istZieldifferent' => true]);
    }

    public function withIstDaZFoerderung(): Factory
    {
        return $this->state(fn () => ['istDaZFoerderung' => true]);
    }
}
