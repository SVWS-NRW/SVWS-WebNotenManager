<?php

use App\Models\Daten;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jahrgaenge', function (Blueprint $table) {
            $table->id();
            $table->string('kuerzel');
            $table->string('kuerzelAnzeige');
            $table->string('beschreibung');
            $table->string('stufe');
            $table->integer('sortierung');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jahrgaenge');
    }
};