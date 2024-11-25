<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * Seeder class responsible for populating the database with initial data.
 *
 * @package Database\Seeders
 */
class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the seeder.
     *
     * @return void
     */
    public function run(): void
    {
        // Create a default administrator user
        User::factory()->create([
			'email' => 'user@test.com',
			'email_valid' => true,
			'is_administrator' => true,
		]);
    }
}
