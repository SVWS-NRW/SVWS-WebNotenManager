<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(table: 'daten', callback: function (Blueprint $table): void {
            $table->id();
            $table->integer(column: 'enmRevision');
            $table->integer(column: 'schulnummer');
            $table->integer(column: 'schuljahr');
            $table->integer(column: 'anzahlAbschnitte');
            $table->integer(column: 'aktuellerAbschnitt');
            $table->string(column: 'publicKey')->nullable();
			$table->integer(column: 'lehrerID')->unique()->comment(comment: 'API LehrerID value');
            $table->foreignIdFor(User::class)->comment(comment: 'User/Lehrer model relation');
            $table->boolean(column: 'fehlstundenEingabe')->default(value: false);
            $table->boolean(column: 'fehlstundenSIFachbezogen')->default(value: false);
            $table->boolean(column: 'fehlstundenSIIFachbezogen')->default(value: false);
            $table->string(column: 'schulform');
            $table->string(column: 'mailadresse')->nullable();
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists(table: 'daten');
    }
};
