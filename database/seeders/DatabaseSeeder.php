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
        User::factory()->create(attributes: [
			'email' => 'user@user.com',
			'is_administrator' => true,
		]);

        $this->call(class: [
            JsonImportSeeder::class,
            SettingSeeder::class,
        ]);
    }
}
