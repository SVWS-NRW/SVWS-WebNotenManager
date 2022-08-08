<?php

namespace Database\Factories;

use App\Models\Kurs;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kurs>
 */
class KursFactory extends Factory
{
    protected $model = Kurs::class;

    public function definition(): array
    {
        return [
            'ext_id' => $this->faker->unique(true)->randomNumber(),
            'bezeichnung' => $this->faker->unique->text(),
            'kuerzel' => $this->faker->unique->word(),
        ];
    }
}
