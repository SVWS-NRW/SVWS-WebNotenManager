<?php

namespace Database\Factories;

use App\Models\Jahrgang;
use App\Models\Klasse;
use App\Models\Schueler;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            'geschlecht' => $this->faker->randomElement(array: Schueler::GENDERS),
        ];
    }

    public function bilingualeSprache(): Factory
    {
        return $this->state(fn (): array  => [
			'bilingualeSprache' => $this->faker->unique->word(),
		]);
    }

    public function istZieldifferent(): Factory
    {
        return $this->state(fn (): array  => [
			'istZieldifferent' => true,
		]);
    }

    public function istDaZFoerderung(): Factory
    {
        return $this->state(fn (): array  => [
			'istDaZFoerderung' => true,
		]);
    }
}
