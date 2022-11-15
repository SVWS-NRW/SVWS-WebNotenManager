<?php

use App\Models\Daten;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('foerderschwerpunkte', function (Blueprint $table) {
            $table->id();
            $table->string('kuerzel')->unique();
            $table->string('beschreibung');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('foerderschwerpunkte');
    }
};
