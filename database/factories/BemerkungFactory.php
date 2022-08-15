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
            'shueler_id' => Schueler::factory(),
        ];
    }

    public function withAue(): Factory
    {
        return $this->state(fn () => ['aue' => $this->faker->unique->word()]);
    }

    public function withAsv(): Factory
    {
        return $this->state(fn () => ['asv' => $this->faker->unique->word()]);
    }

    public function withZb(): Factory
    {
        return $this->state(fn () => ['zb' => $this->faker->unique->word()]);
    }

    public function withLels(): Factory
    {
        return $this->state(fn () => ['lels' => $this->faker->unique->word()]);
    }

    public function withSchulformEmpf(): Factory
    {
        return $this->state(fn () => ['schulformEmpf' => $this->faker->unique->word()]);
    }

    public function withIndividuelleVersetzungsbemerkungen(): Factory
    {
        return $this->state(fn () => ['individuelleVersetzungsbemerkungen' => $this->faker->unique->word()]);
    }

    public function withFoerderbemerkungen(): Factory
    {
        return $this->state(fn () => ['foerderbemerkungen' => $this->faker->unique->word()]);
    }
}
