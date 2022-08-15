<?php

use App\Models\Daten;
use App\Models\Fach;
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
            $table->integer('ext_id')->unique();
            $table->integer('groupable_id');
            $table->string('groupable_type');
            $table->foreignIdFor(Fach::class);
            $table->string('kursartID')->nullable();
            $table->string('bezeichnung');
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
