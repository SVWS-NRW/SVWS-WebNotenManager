<?php

use App\Models\Lerngruppe;
use App\Models\Note;
use App\Models\Schueler;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leistungen', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Schueler::class);
            $table->foreignIdFor(Lerngruppe::class);
            $table->foreignIdFor(Note::class)->nullable();
            $table->boolean('istSchriftlich')->default(false);
            $table->string('abiturfach')->nullable();
            $table->integer('fehlstundenGesamt')->nullable();
            $table->integer('fehlstundenUnentschuldigt')->nullable();
            $table->text('fachbezogeneBemerkungen')->nullable();
            $table->string('neueZuweisungKursart')->nullable();
            $table->boolean('istGemahnt')->default(false);
            $table->timestamp('mahndatum')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leistungen');
    }
};
