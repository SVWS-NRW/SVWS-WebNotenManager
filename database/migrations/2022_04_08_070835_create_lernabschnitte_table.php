<?php

use App\Models\Schueler;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lernabschnitte', function (Blueprint $table) {
            $table->id();            
            $table->integer('ext_id')->unique();
            $table->foreignIdFor(Schueler::class);
            $table->string('pruefungsordnung');
            $table->unsignedBigInteger('lernbereich1note')->nullable();
            $table->unsignedBigInteger('lernbereich2note')->nullable();
            $table->unsignedBigInteger('foerderschwerpunkt1')->nullable();
            $table->unsignedBigInteger('foerderschwerpunkt2')->nullable();
            $table->timestamps();
            
            $table->foreign('lernbereich1note')->references('id')->on('noten');
            $table->foreign('lernbereich2note')->references('id')->on('noten');
            $table->foreign('foerderschwerpunkt1')->references('id')->on('foerderschwerpunkte');
            $table->foreign('foerderschwerpunkt2')->references('id')->on('foerderschwerpunkte');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lernabschnitte');
    }
};
