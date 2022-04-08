<?php

use App\Models\Fach;
use App\Models\Jahrgang;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('floskeln', function (Blueprint $table) {
            $table->id();
            $table->string('kuerzel');
            $table->foreignIdFor(Fach::class)->nullable();
            $table->integer('niveau')->nullable();
            $table->foreignIdFor(Jahrgang::class)->nullable();
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('floskeln');
    }
};
