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
            $table->foreignIdFor(Note::class);
            $table->boolean('istSchriftlich')->default(false);
            $table->string('abiturfach');
            $table->integer('fehlstundenGesamt')->nullable();
            $table->integer('fehlstundenUnentschuldigt')->nullable();
            $table->string('fachbezogeneBemerkungen')->nullable();
            $table->unsignedBigInteger('neueZuweisungKursart')->nullable();
            $table->timestamps();
            
            $table->foreign('neueZuweisungKursart')->references('id')->on('kurse');
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('leistungen');
    }
};
