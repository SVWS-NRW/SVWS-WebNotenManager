<?php

use App\Models\Fach;
use App\Models\Klasse;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(table: 'lerngruppen', callback: function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(model: Klasse::class)->nullable();
            $table->foreignIdFor(model: Fach::class);
            $table->string(column: 'kID');
            $table->integer(column: 'kursartID')->nullable();
            $table->string(column: 'bezeichnung');
            $table->string(column: 'kursartKuerzel')->nullable();
            $table->string(column: 'bilingualeSprache')->nullable();
            $table->integer(column: 'wochenstunden');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(table: 'lerngruppen');
    }
};
