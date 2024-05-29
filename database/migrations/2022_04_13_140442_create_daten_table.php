<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('daten', function (Blueprint $table): void {
            $table->id();
            $table->integer('enmRevision');
            $table->integer('schulnummer');
            $table->integer('schuljahr');
            $table->integer('anzahlAbschnitte');
            $table->integer('aktuellerAbschnitt');
            $table->string('publicKey')->nullable();
			$table->integer('lehrerID')->unique()->comment('API LehrerID value');
            $table->foreignIdFor(User::class)->comment('User/Lehrer model relation');
            $table->boolean('fehlstundenEingabe')->default(false);
            $table->boolean('fehlstundenSIFachbezogen')->default(false);
            $table->boolean('fehlstundenSIIFachbezogen')->default(false);
            $table->string('schulform');
            $table->string('mailadresse')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('daten');
    }
};
