<?php

namespace Database\Factories;

use App\Models\Daten;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DatenFactory extends Factory
{
    protected $model = Daten::class;

    public function definition(): array
    {
        return [
            'enmRevision' => rand(min: 1, max: 10),
            'schuljahr' => rand(min: 2000, max: 2022),
            'anzahlAbschnitte' => rand(min: 1, max: 4),
            'aktuellerAbschnitt' => rand(min: 1, max: 4),
            'lehrerID' => $this->faker->unique()->numberBetween(int1: 1, int2: 1_000_000),
            'user_id' => User::factory(),
        ];
    }

    public function withPublicKey(): Factory
    {
        return $this->state(fn (): array  => [
			'publicKey' => $this->faker->word,
		]);
    }

    public function withFehlstundenEingabe(): Factory
    {
        return $this->state(fn (): array  => [
			'fehlstundenEingabe' => true,
		]);
    }

    public function withFehlstundenSIFachbezogen(): Factory
    {
        return $this->state(fn (): array  => [
			'fehlstundenSIFachbezogen' => true,
		]);
    }

    public function withFehlstundenSIIFachbezogen(): Factory
    {
        return $this->state(fn (): array  => [
			'fehlstundenSIIFachbezogen' => true,
		]);
    }

    public function withSchulform(): Factory
    {
        return $this->state(fn (): array  => [
			'schulform' => $this->faker->word(),
		]);
    }

    public function withMailadresse(): Factory
    {
        return $this->state(fn (): array  => [
			'mailadresse' => $this->faker->safeEmail(),
		]);
    }
}
