<?php

use App\Models\BKAbschluss;
use App\Models\Fach;
use App\Models\Lehrer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bkfaecher', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(BKAbschluss::class);
            $table->foreignIdFor(Fach::class);
            $table->foreignIdFor(Lehrer::class);
            $table->boolean('istSchriftlich')->default(false);
            $table->unsignedBigInteger('vornote');
            $table->unsignedBigInteger('noteSchriftlichePruefung');
            $table->boolean('muendlichePruefung')->default(false);
            $table->boolean('muendlichePruefungFreiwillig')->default(false);
            $table->unsignedBigInteger('noteMuendlichePruefung');
            $table->boolean('istSchriftlichBerufsabschluss')->default(false);
            $table->unsignedBigInteger('noteBerufsabschluss');
            $table->unsignedBigInteger('abschlussnote');
            $table->timestamps();
            
            $table->foreign('vornote')->references('id')->on('noten');
            $table->foreign('noteSchriftlichePruefung')->references('id')->on('noten');
            $table->foreign('noteMuendlichePruefung')->references('id')->on('noten');
            $table->foreign('noteBerufsabschluss')->references('id')->on('noten');   
            $table->foreign('abschlussnote')->references('id')->on('noten');
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('bkfaecher');
    }
};
