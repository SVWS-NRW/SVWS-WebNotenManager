<?php

namespace Database\Factories;

use App\Models\Bemerkung;
use App\Models\Schueler;
use Illuminate\Database\Eloquent\Factories\Factory;

class BemerkungFactory extends Factory
{
    protected $model = Bemerkung::class;

    public function definition(): array
    {
        return [
            'schueler_id' => Schueler::factory(),
        ];
    }

    public function withAUE(): BemerkungFactory
    {
		return $this->withTimestamp(column: 'AUE');
    }

    public function withASV(): BemerkungFactory
    {
		return $this->withTimestamp(column: 'ASV');
    }

    public function withZB(): BemerkungFactory
    {
		return $this->withTimestamp(column: 'ZB');
    }

    public function withLELS(): BemerkungFactory
    {
        return $this->state(fn (): array => [
			'LELS' => $this->faker->unique->paragraph()
		]);
    }

    public function withSchulformEmpf(): BemerkungFactory
    {
        return $this->state(fn (): array => [
			'schulformEmpf' => $this->faker->unique->paragraph(),
		]);
    }

    public function withIndividuelleVersetzungsbemerkungen(): BemerkungFactory
    {
		return $this->withTimestamp(column: 'individuelleVersetzungsbemerkungen');
    }

    public function withFoerderbemerkungen(): BemerkungFactory
    {
        return $this->state(fn (): array => [
			'foerderbemerkungen' => $this->faker->unique->paragraph(),
		]);
    }

	private function withTimestamp(
		string $column,
		string|null $tsColumn = null,
		string|bool|int|null $value = null
	): BemerkungFactory {
		return $this->state(fn () => [
			$column => $value ?? $this->faker->paragraph(),
			$tsColumn ?? "ts{$column}" => now()->format(format: 'Y-m-d H:i:s.u'),
		]);
	}
}
