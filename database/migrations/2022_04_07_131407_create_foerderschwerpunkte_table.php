<?php

use App\Models\Daten;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(table: 'foerderschwerpunkte', callback: function (Blueprint $table): void {
            $table->id();
            $table->string(column: 'kuerzel')->unique();
            $table->text(column: 'beschreibung');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(table: 'foerderschwerpunkte');
    }
};
