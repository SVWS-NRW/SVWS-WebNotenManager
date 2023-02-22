<?php

use App\Models\Lerngruppe;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(table: 'lerngruppe_user', callback: function (Blueprint $table): void {
            $table->foreignIdFor(model: User::class);
            $table->foreignIdFor(model: Lerngruppe::class);
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists(table: 'lerngruppe_user');
    }
};
