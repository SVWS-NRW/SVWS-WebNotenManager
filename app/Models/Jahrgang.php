<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jahrgang extends Model
{
    use HasFactory;

    protected $table = 'jahrgaenge';

    protected $fillable = [
        'kuerzel',
        'kuerzelAnzeige',
        'beschreibung',
        'stufe',
        'sortierung',
    ];
}
