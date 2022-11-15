<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('klassen', function (Blueprint $table) {
            $table->id();
            $table->integer('ext_id')->unique();
            $table->string('kuerzel');
            $table->string('kuerzelAnzeige');
            $table->integer('sortierung');
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('klassen');
    }
};
