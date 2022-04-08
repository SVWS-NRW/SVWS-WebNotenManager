<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Leistung extends Model
{
    use HasFactory;
    
    protected $table = 'leistungen';

    protected $fillable = [
        'schueler_id', 
        'lerngruppe_id',
        'note_id',
        'istSchriftlich',
        'abiturfach',
        'fehlstundenGesamt',
        'fehlstundenUnentschuldigt',
        'fachbezogeneBemerkungen',
        'neueZuweisungKursart',
    ];

    public function lerngruppe(): BelongsTo
    {
        return $this->belongsTo(Lerngruppe::class);
    }  

    public function neueZuweisungKursart(): BelongsTo
    {
        return $this->belongsTo(Kurs::class);
    }  

    public function note(): BelongsTo
    {
        return $this->belongsTo(Note::class);
    }  

    public function schuler(): BelongsTo
    {
        return $this->belongsTo(Schueler::class);
    }  

    public function teilleistungen(): HasMany
    {
        return $this->hasMany(Teilleistung::class);
    }
}
