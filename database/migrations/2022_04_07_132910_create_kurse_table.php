<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kurse', function (Blueprint $table) {
            $table->id();
            $table->integer('ext_id')->unique();
            $table->string('kuerzel');
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('kurse');
    }
};
