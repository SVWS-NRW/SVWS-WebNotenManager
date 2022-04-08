<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faecher', function (Blueprint $table) {
            $table->id();
            $table->string('kuerzel');
            $table->string('kuerzelAnzeige');
            $table->integer('sortierung');
            $table->boolean('istFremdsprache')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faecher');
    }
};
