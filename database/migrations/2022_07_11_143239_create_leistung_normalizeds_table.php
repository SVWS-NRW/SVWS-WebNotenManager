<?php

use App\Models\Leistung;
use App\Models\Lerngruppe;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leistung_normalized', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Leistung::class);
            $table->foreignIdFor(Lerngruppe::class);
            $table->string('klasse')->nullable();
            $table->string('vorname');
            $table->string('nachname');
			$table->char('geschlecht', 1);
            $table->string('jahrgang');
            $table->string('fach')->nullable();
            $table->string('lehrer');
            $table->string('kurs')->nullable();
            $table->string('note')->nullable();
            $table->text('fachbezogeneBemerkungen')->nullable();
            $table->boolean('istGemahnt')->default(false);
			$table->timestamp('mahndatum')->nullable();
			$table->integer('fs')->default(0);
			$table->integer('ufs')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leistung_normalized');
    }
};
