<?php

namespace Database\Factories;

use App\Models\Bemerkung;
use App\Models\Schueler;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bemerkung>
 */
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
		return $this->withTimestamp('AUE');
    }

    public function withASV(): BemerkungFactory
    {
		return $this->withTimestamp('ASV');
    }

    public function withZB(): BemerkungFactory
    {
		return $this->withTimestamp('ZB');
    }

    public function withLELS(): BemerkungFactory
    {
        return $this->state(fn () => ['LELS' => $this->faker->unique->paragraph()]);
    }

    public function withSchulformEmpf(): BemerkungFactory
    {
        return $this->state(fn () => ['schulformEmpf' => $this->faker->unique->paragraph()]);
    }

    public function withIndividuelleVersetzungsbemerkungen(): BemerkungFactory
    {
		return $this->withTimestamp('individuelleVersetzungsbemerkungen');
    }

    public function withFoerderbemerkungen(): BemerkungFactory
    {
        return $this->state(fn () => ['foerderbemerkungen' => $this->faker->unique->paragraph()]);
    }

	private function withTimestamp(
		string $column,
		string|null $tsColumn = null,
		string|bool|int|null $value = null
	): BemerkungFactory {
		return $this->state(fn () => [
			$column => $value ?? $this->faker->paragraph(),
			$tsColumn ?? "ts{$column}" => now()->format('Y-m-d H:i:s.u'),
		]);
	}
}
