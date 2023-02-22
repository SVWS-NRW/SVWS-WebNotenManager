<?php

use App\Models\Klasse;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(table: 'klasse_user', callback: function (Blueprint $table): void {
            $table->foreignIdFor(model: Klasse::class);
            $table->foreignIdFor(model: User::class);
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists(table: 'klasse_user');
    }
};
