<?php

use App\Models\Leistung;
use App\Models\Note;
use App\Models\Teilleistung;
use App\Models\Teilleistungsart;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teilleistungen', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Leistung::class);
            $table->foreignIdFor(Teilleistungsart::class);
            $table->date('datum')->nullable();
            $table->string('bemerkung')->nullable();
            $table->foreignIdFor(Note::class)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teilleistungen');
    }
};
