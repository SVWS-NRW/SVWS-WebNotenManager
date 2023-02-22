<?php

use App\Models\BKAbschluss;
use App\Models\Fach;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(table: 'bkfaecher', callback: function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(model: BKAbschluss::class);
            $table->foreignIdFor(model: Fach::class);
            $table->foreignIdFor(model: User::class);
            $table->boolean(column: 'istSchriftlich')->default(value: false);
            $table->unsignedBigInteger(column: 'vornote');
            $table->unsignedBigInteger(column: 'noteSchriftlichePruefung');
            $table->boolean(column: 'muendlichePruefung')->default(false);
            $table->boolean(column: 'muendlichePruefungFreiwillig')->default(value: false);
            $table->unsignedBigInteger(column: 'noteMuendlichePruefung');
            $table->boolean(column: 'istSchriftlichBerufsabschluss')->default(value: false);
            $table->unsignedBigInteger(column: 'noteBerufsabschluss');
            $table->unsignedBigInteger(column: 'abschlussnote');
            $table->timestamps();
            
            $table->foreign(columns: 'vornote')->references(columns: 'id')->on(table: 'noten');
            $table->foreign(columns: 'noteSchriftlichePruefung')->references(columns: 'id')->on(table: 'noten');
            $table->foreign(columns: 'noteMuendlichePruefung')->references(columns: 'id')->on(table: 'noten');
            $table->foreign(columns: 'noteBerufsabschluss')->references(columns: 'id')->on(table: 'noten');
            $table->foreign(columns: 'abschlussnote')->references(columns: 'id')->on(table: 'noten');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(table: 'bkfaecher');
    }
};
