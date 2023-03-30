<?php

use App\Models\Jahrgang;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(table: 'klassen', callback: function (Blueprint $table): void {
            $table->id();
			$table->foreignIdFor(model: Jahrgang::class, column: 'idJahrgang');
            $table->string(column: 'kuerzel');
            $table->string(column: 'kuerzelAnzeige');
            $table->integer(column: 'sortierung');
			$table->boolean(column: 'editable_teilnoten')->default(value: true);
			$table->boolean(column: 'editable_noten')->default(value: true);
			$table->boolean(column: 'editable_mahnungen')->default(value: true);
			$table->boolean(column: 'editable_fehlstunden')->default(value: true);
			$table->boolean(column: 'editable_fb')->default(value: true);
			$table->boolean(column: 'editable_asv')->default(value: true);
			$table->boolean(column: 'editable_aue')->default(value: true);
			$table->boolean(column: 'editable_zb')->default(value: true);
		});
    }
    
    public function down(): void
    {
        Schema::dropIfExists(table: 'klassen');
    }
};
