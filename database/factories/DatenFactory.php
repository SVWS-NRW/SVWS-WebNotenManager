<?php

namespace Database\Factories;

use App\Models\Daten;
use App\Models\User as Lehrer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Data>
 */
class DatenFactory extends Factory
{
    protected $model = Daten::class;

    public function definition(): array
    {
        return [
            'enmRevision' => rand(1, 10),
            'schuljahr' => rand(2000, 2022),
            'anzahlAbschnitte' => rand(1, 4),
            'aktuellerAbschnitt' => rand(1, 4),  
            'lehrerID' => $this->faker->unique()->numberBetween(1, 1_000_000),
            'user_id' => Lehrer::factory(),
        ];
    }

    public function withPublicKey(): Factory
    {
        return $this->state(fn () => ['publicKey' => $this->faker->word]);
    }

    public function withFehlstundenEingabe(): Factory
    {
        return $this->state(fn () => ['fehlstundenEingabe' => true]);
    }

    public function withFehlstundenSIFachbezogen(): Factory
    {
        return $this->state(fn () => ['fehlstundenSIFachbezogen' => true]);
    }

    public function withFehlstundenSIIFachbezogen(): Factory
    {
        return $this->state(fn () => ['fehlstundenSIIFachbezogen' => true]);
    }

    public function withSchulform(): Factory
    {
        return $this->state(fn () => ['schulform' => $this->faker->word()]);
    }

    public function withMailadresse(): Factory
    {
        return $this->state(fn () => ['mailadresse' => $this->faker->safeEmail()]);
    }
}
