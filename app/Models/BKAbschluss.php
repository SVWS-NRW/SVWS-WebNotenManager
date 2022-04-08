<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BKAbschluss extends Model
{
    use HasFactory;

    protected $table = 'bkabschluesse';

    protected $fillable = [
        'schueler_id', 
        'hatZulassung', 
        'hatBestanden', 
        'hatZulassungErweiterteBeruflicheKenntnisse', 
        'hatErworbenErweiterteBeruflicheKenntnisse', 
        'notePraktischePruefung', 
        'noteKolloqium', 
        'hatZulassungBerufsabschlusspruefung', 
        'hatBestandenBerufsabschlusspruefung', 
        'themaAbschlussarbeit', 
        'istVorhandenBerufsabschlusspruefung', 
        'noteFachpraxis', 
        'istFachpraktischerTeilAusreichend', 
    ];  

    public function noteFachpraxis(): BelongsTo
    {
        return $this->belongsTo(Note::class);
    }   

    public function noteKolloqium(): BelongsTo
    {
        return $this->belongsTo(Note::class);
    }     

    public function notePraktischePruefung(): BelongsTo
    {
        return $this->belongsTo(Note::class);
    }    

    public function schuler(): BelongsTo
    {
        return $this->belongsTo(Schueler::class);
    } 
}
