<?php

namespace Database\Factories;

use App\Models\Team;
use App\Models\User as Lehrer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;

class UserFactory extends Factory
{
    protected $model = Lehrer::class;
    
    public function definition()
    {
        return [                        
            'kuerzel' => $this->faker->unique->word(),
            'nachname' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'remember_token' => Str::random(10),
        ];
    }

    public function withExtId(): Factory
    {
        return $this->state(fn () => ['ext_id' => $this->faker->unique(true)->randomNumber()]);
    }    

    public function unverified(): Factory
    {
        return $this->state(fn () => ['email_verified_at' => null]);
    }

    public function withPersonalTeam(): Factory
    {
        if (! Features::hasTeamFeatures()) {
            return $this->state([]);
        }

        return $this->has(
            Team::factory()->state(fn (array $attributes, Lehrer $lehrer) => [
                'name' => $lehrer->kuerzel.'\'s Team', 
                'user_id' => $lehrer->id, 
                'personal_team' => true
            ]),
            'ownedTeams'
        );
    }
}
