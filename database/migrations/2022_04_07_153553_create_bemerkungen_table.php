<?php

use App\Models\Lerngruppe;
use App\Models\Note;
use App\Models\Schueler;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bemerkungen', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Schueler::class);
            $table->text('asv')->nullable();
            $table->text('aue')->nullable();
            $table->text('zb')->nullable();
            $table->string('lels')->nullable();
            $table->string('schulformEmpf')->nullable();
            $table->string('individuelleVersetzungsbemerkungen')->nullable();
            $table->string('foerderbemerkungen')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bemerkungen');
    }
};
