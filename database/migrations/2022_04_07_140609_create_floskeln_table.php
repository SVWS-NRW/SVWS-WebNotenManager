<?php

use App\Models\Fach;
use App\Models\Floskelgruppe;
use App\Models\Jahrgang;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(table: 'floskeln', callback: function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(model: Floskelgruppe::class);
            $table->string(column: 'kuerzel');
            $table->text(column: 'text');
            $table->foreignIdFor(model: Fach::class)->nullable();
            $table->foreignIdFor(model: Jahrgang::class)->nullable();
            $table->integer(column: 'niveau')->nullable();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists(table: 'floskeln');
    }
};
