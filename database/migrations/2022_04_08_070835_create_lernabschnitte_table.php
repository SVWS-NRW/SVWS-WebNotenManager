<?php

use App\Models\Schueler;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(table: 'lernabschnitte', callback: function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(model: Schueler::class);
			$table->integer(column: 'fehlstundenGesamt')->default(value: 0);
			$table->timestamp(column: 'tsFehlstundenGesamt', precision: 3)->default(value: now());
			$table->integer(column: 'fehlstundenGesamtUnentschuldigt')->default(value: 0);
			$table->timestamp(column: 'tsFehlstundenGesamtUnentschuldigt', precision: 3)->default(value: now());
            $table->string(column: 'pruefungsordnung');
            $table->unsignedBigInteger(column: 'lernbereich1note')->nullable();
            $table->unsignedBigInteger(column: 'lernbereich2note')->nullable();
            $table->unsignedBigInteger(column: 'foerderschwerpunkt1')->nullable();
            $table->unsignedBigInteger(column: 'foerderschwerpunkt2')->nullable();
            $table->timestamps();

            $table->foreign(columns: 'lernbereich1note')->references(columns: 'id')->on(table: 'noten');
            $table->foreign(columns: 'lernbereich2note')->references(columns: 'id')->on(table: 'noten');
            $table->foreign(columns: 'foerderschwerpunkt1')->references(columns: 'id')->on(table: 'foerderschwerpunkte');
            $table->foreign(columns: 'foerderschwerpunkt2')->references(columns: 'id')->on(table: 'foerderschwerpunkte');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(table: 'lernabschnitte');
    }
};
