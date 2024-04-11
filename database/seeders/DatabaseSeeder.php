<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Seeder class responsible for populating the database with initial data.
 *
 * @package Database\Seeders
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Run the seeder.
     *
     * @return void
     */
    public function run(): void
    {
        // Create a default administrator user
        User::factory()->create([
			'email' => 'user@user.com',
			'is_administrator' => true,
		]);

        // Call other specific seeders to populate additional data
        $this->call([
            // TODO: To be removed, temporary testing route #239 by Karol
            // JsonImportSeeder::class,
        ]);
    }
}
