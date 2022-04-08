<?php

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
            $table->integer('groupable_id');            
            $table->string('groupable_type');
            $table->foreignIdFor(Fach::class);
            $table->string('bezeichnung');
            $table->unsignedBigInteger('bilingualeSprache')->nullable();
            $table->integer('wochenstunden');
            $table->timestamps();
            
            $table->foreign('bilingualeSprache')->references('id')->on('faecher');
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('lerngruppen');
    }
};
