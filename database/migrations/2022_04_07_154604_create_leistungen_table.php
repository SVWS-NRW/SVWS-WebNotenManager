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
        Schema::create(table:'leistungen', callback: function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(model: Schueler::class);
            $table->foreignIdFor(model: Lerngruppe::class);
            $table->foreignIdFor(model: Note::class)->nullable();
			$table->timestamp(column: 'tsNote', precision: 3)->default(value: now());
            $table->boolean(column: 'istSchriftlich')->default(value: false);
            $table->string(column: 'abiturfach')->nullable();
            $table->integer(column: 'fehlstundenFach')->nullable();
			$table->timestamp(column: 'tsFehlstundenFach', precision: 3)->default(value: now());
            $table->integer(column: 'fehlstundenUnentschuldigtFach')->nullable();
			$table->timestamp(column: 'tsFehlstundenUnentschuldigtFach', precision: 3)->default(value: now());
            $table->text(column: 'fachbezogeneBemerkungen')->nullable();
			$table->timestamp(column: 'tsFachbezogeneBemerkungen', precision: 3)->default(value: now());
            $table->string(column: 'neueZuweisungKursart')->nullable();
            $table->boolean(column: 'istGemahnt')->default(false);
			$table->timestamp(column: 'tsIstGemahnt', precision: 3)->default(value: now());
            $table->timestamp(column: 'mahndatum')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(table: 'leistungen');
    }
};
