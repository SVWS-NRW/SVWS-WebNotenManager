<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lehrer', function (Blueprint $table) {
			$table->id();
			$table->string('kuerzel');
			$table->string('vorname');
			$table->string('nachname');
			$table->string('geschlecht');
			$table->string('email')->unique();
			$table->timestamp('email_verified_at')->nullable();
			$table->string('password');
			$table->boolean('administrator')->default(false);
			$table->rememberToken();
			$table->foreignId('current_team_id')->nullable();
			$table->string('profile_photo_path', 2048)->nullable();
			$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lehrer');
    }
};