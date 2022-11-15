<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teilleistungsarten', function (Blueprint $table) {
            $table->id();
            $table->string('bezeichnung');
            $table->integer('sortierung')->nullable();
            $table->double('gewichtung')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teilleistungsarten');
    }
};
