<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SettingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'type' => $this->faker->unique()->word(),
            'key' => $this->faker->unique()->word(),
            'value' => $this->faker->unique()->paragraph(),
        ];
    }
}
