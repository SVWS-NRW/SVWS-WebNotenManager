<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Schueler extends Model
{
    use HasFactory;

    protected $table = 'schueler';

    protected $fillable = [
        'jahrgang_id',
        'klasse_id',
        'nachname',
        'vorname',
        'bilingualeSprache', 
        'istZieldifferent',
        'istDaZFoerderung',
    ];

    public function bilingualeSprache(): BelongsTo
    {
        return $this->belongsTo(Fach::class);
    }

    public function bemerkungen(): HasMany
    {
        return $this->hasMany(Bemerkung::class);
    }

    public function bkabschluesse(): HasMany
    {
        return $this->hasMany(BKAbschluss::class);
    }

    public function jahrgang(): BelongsTo
    {
        return $this->belongsTo(Jahrgang::class);
    }

    public function klasse(): BelongsTo
    {
        return $this->belongsTo(Klasse::class);
    }

    public function leistungen(): HasMany
    {
        return $this->hasMany(Leistung::class);
    }

    public function sprachenfolgen(): HasMany
    {
        return $this->hasMany(Sprachenfolge::class);
    }

    public function lernabschnitt(): HasOne
    {
        return $this->hasOne(Lernabschnitt::class);
    }

    public function zp10(): HasMany
    {
        return $this->hasMany(Zp10::class);
    }
}
