<?php

use App\Models\Fach;
use App\Models\Schueler;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sprachenfolge', function (Blueprint $table) {
            $table->id();            
            $table->foreignIdFor(Schueler::class);
            $table->foreignIdFor(Fach::class);
            $table->integer('reihenfolge');
            $table->integer('belegungVonJahrgang')->nullable();
            $table->integer('belegungVonAbschnitt')->nullable();
            $table->integer('belegungBisJahrgang')->nullable();
            $table->integer('belegungBisAbschnitt')->nullable();
            $table->string('referenzniveau')->nullable();
            $table->integer('belegungSekI')->nullable();
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('sprachenfolge');
    }
};