<?php

use App\Models\Daten;
use App\Models\Fach;
use App\Models\Klasse;
use App\Models\Lehrer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lerngruppen', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Klasse::class)->nullable();
            $table->foreignIdFor(Fach::class);
            $table->string('kID');
            $table->string('bezeichnung');
            $table->string('kursartKuerzel')->nullable();
            $table->string('bilingualeSprache')->nullable();
            $table->integer('wochenstunden');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lerngruppen');
    }
};