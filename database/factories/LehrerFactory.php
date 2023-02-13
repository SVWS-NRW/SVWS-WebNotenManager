<?php

namespace Database\Factories;

use App\Models\Lehrer;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;

class LehrerFactory extends Factory
{
	protected $model = Lehrer::class;

	public function definition(): array
	{
		return [
			'kuerzel' => $this->faker->unique->word(),
			'vorname' => $this->faker->firstName(),
			'nachname' => $this->faker->lastName(),
			'geschlecht' => $this->faker->randomElement(array: Lehrer::GENDERS),
			'email' => $this->faker->unique()->safeEmail(),
			'email_verified_at' => now(),
			'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
			'remember_token' => Str::random(length: 10),
		];
	}

	public function unverified(): Factory
	{
		return $this->state(fn (): array  => [
			'email_verified_at' => null,
		]);
	}

	public function administrator(): Factory
	{
		return $this->state(fn (): array  => [
			'administrator' => true,
		]);
	}

	public function withPersonalTeam(): Factory
	{
		if (! Features::hasTeamFeatures()) {
			return $this->state([]);
		}

		return $this->has(
			Team::factory()->state(state: fn (array $attributes, Lehrer $lehrer): array => [
				'name' => $lehrer->kuerzel.'\'s Team',
				'lehrer_id' => $lehrer->id,
				'personal_team' => true
			]),
			relationship: 'ownedTeams'
		);
	}
}
