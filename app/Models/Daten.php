<?php

namespace App\Models;


use App\Models\User as Lehrer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Daten extends Model
{
    use HasFactory;

    protected $table = 'daten';

    protected $fillable = [
        'enmRevision',
        'schuljahr',
        'anzahlAbschnitte',
        'aktuellerAbschnitt',
        'publicKey',
        'lehrer_id',
        'fehlstundenEingabe',
        'fehlstundenSIFachbezogen',
        'fehlstundenSIIFachbezogen',
        'schulform',
        'mailadresse',
    ];

    public function lehrer(): BelongsTo
    {
        return $this->belongsTo(Lehrer::class);
    }
}
