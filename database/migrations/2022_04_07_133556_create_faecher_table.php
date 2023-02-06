<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(table: 'faecher', callback: function (Blueprint $table): void {
            $table->id();
            $table->string(column: 'kuerzel');
            $table->string(column: 'kuerzelAnzeige');
            $table->integer(column: 'sortierung');
            $table->boolean(column: 'istFremdsprache')->default(value: false);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(table: 'faecher');
    }
};
