<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(table: 'settings', callback: function (Blueprint $table): void {
            $table->id();
            $table->string(column: 'group')->index();
            $table->string(column: 'name');
            $table->boolean(column: 'locked');
            $table->json(column: 'payload');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(table: 'settings');
    }
};
