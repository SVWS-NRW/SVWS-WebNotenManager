<?php

use App\Models\Daten;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('noten', function (Blueprint $table) {
            $table->id();
            $table->string('kuerzel')->unique();
            $table->integer('notenpunkte')->nullable();
            $table->string('text')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('noten');
    }
};
