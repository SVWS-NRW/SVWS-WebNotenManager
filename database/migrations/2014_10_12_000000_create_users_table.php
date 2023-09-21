<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(table: 'users', callback: function (Blueprint $table): void {
			$table->id();
			$table->integer(column: 'ext_id')->unique()->nullable();
			$table->string(column: 'kuerzel');
			$table->string(column: 'vorname');
			$table->string(column: 'nachname');
			$table->string(column: 'geschlecht');
			$table->string(column: 'email')->unique();
			$table->timestamp(column: 'email_verified_at')->nullable();
			$table->string(column: 'password');
			$table->boolean(column: 'is_administrator')->default(value: false);
			$table->rememberToken();
			$table->foreignId(column: 'current_team_id')->nullable();
			$table->string(column: 'profile_photo_path', length: 2048)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(table: 'users');
    }
};
