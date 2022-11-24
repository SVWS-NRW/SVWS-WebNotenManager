<?php

use App\Models\Klasse;
use App\Models\Lehrer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('klasse_lehrer', function (Blueprint $table) {
            $table->foreignIdFor(Klasse::class);
            $table->foreignIdFor(Lehrer::class);
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('klasse_lehrer');
    }
};
