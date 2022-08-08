<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        \App\Models\User::factory()->create(['email' => 'user@cgi.com']);
        $this->call([
            JsonImportSeeder::class,
        ]);
    }
}
