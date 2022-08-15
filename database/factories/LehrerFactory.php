<?php

namespace Database\Factories;

use App\Models\Lehrer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lehrer>
 */
class LehrerFactory extends Factory
{
    protected $model = Lehrer::class;

    public function definition(): array
    {
        return [
            'kuerzel' => $this->faker->unique->word(),
            'nachname' => $this->faker->lastName(),
        ];
    }

    public function withVorname(): Factory
    {
        return $this->state(fn () => ['vorname' => $this->faker->firstName()]);
    }
}
