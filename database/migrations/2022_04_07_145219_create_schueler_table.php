<?php

use App\Models\Jahrgang;
use App\Models\Klasse;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schueler', function (Blueprint $table) {
            $table->id();
            $table->integer('ext_id')->unique();
            $table->foreignIdFor(Jahrgang::class);
            $table->foreignIdFor(Klasse::class);
            $table->string('nachname');
            $table->string('vorname');
            $table->string('bilingualeSprache')->nullable();
            $table->boolean('istZieldifferent')->default(false);
            $table->boolean('istDaZFoerderung')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schueler');
    }
};
