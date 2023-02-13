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

    public function withBilingualeSprache(): Factory
    {
        return $this->state(fn (): array  => [
			'bilingualeSprache' => $this->faker->unique->word(),
		]);
    }

    public function withIstZieldifferent(): Factory
    {
        return $this->state(fn (): array  => [
			'istZieldifferent' => true,
		]);
    }

    public function withIstDaZFoerderung(): Factory
    {
        return $this->state(fn (): array  => [
			'istDaZFoerderung' => true,
		]);
    }

	public function withAue(): Factory
	{
		return $this->state(fn (): array  => [
			'aue' => $this->faker->unique->word(),
		]);
	}

	public function withAsv(): Factory
	{
		return $this->state(fn (): array  => [
			'asv' => $this->faker->unique->word(),
		]);
	}

	public function withZb(): Factory
	{
		return $this->state(fn (): array  => [
			'zb' => $this->faker->unique->word(),
		]);
	}

	public function withLels(): Factory
	{
		return $this->state(fn (): array  => [
			'lels' => $this->faker->unique->word(),
		]);
	}

	public function withSchulformEmpf(): Factory
	{
		return $this->state(fn (): array  => [
			'schulformEmpf' => $this->faker->unique->word(),
		]);
	}

	public function withIndividuelleVersetzungsbemerkungen(): Factory
	{
		return $this->state(fn (): array  => [
			'individuelleVersetzungsbemerkungen' => $this->faker->unique->word(),
		]);
	}

	public function withFoerderbemerkungen(): Factory
	{
		return $this->state(fn (): array  => [
			'foerderbemerkungen' => $this->faker->unique->word(),
		]);
	}
}
