<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sprachenfolge extends Model
{
    use HasFactory;

    protected $table = 'sprachenfolge';

    protected $fillable = [           
        'schuler_id',
        'sprache', 
        'fach_id',
        'reihenfolge',
        'belegungVonJahrgang',
        'belegungVonAbschnitt',
        'belegungBisJahrgang',
        'belegungBisAbschnitt',
        'referenzniveau',
        'belegungSekI',
    ];

    public function fach(): BelongsTo
    {
        return $this->belongsTo(Fach::class);
    }

    public function schueler(): BelongsTo
    {
        return $this->belongsTo(Schueler::class);
    }
}
