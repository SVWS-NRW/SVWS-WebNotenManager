<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->administrator()->create(['email' => 'user@user.com']);

        $this->call([
            JsonImportSeeder::class,
            SettingSeeder::class,
        ]);
    }
}
