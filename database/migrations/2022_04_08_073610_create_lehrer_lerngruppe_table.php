<?php

use App\Models\User as Lehrer;
use App\Models\Lerngruppe;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lehrer_lerngruppe', function (Blueprint $table) {
            $table->foreignIdFor(Lehrer::class);
            $table->foreignIdFor(Lerngruppe::class);
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('lehrer_lerngruppe');
    }
};
