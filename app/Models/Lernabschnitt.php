<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lernabschnitt extends Model
{
    use HasFactory;

    protected $table = 'lernabschnitte';

    protected $fillable = [     
        'ext_id',   
        'schuler_id',
        'pruefungsordnung',
        'lernbereich1note',
        'lernbereich2note',
        'foerderschwerpunkt1',
        'foerderschwerpunkt2',
    ];

    public function foerderschwerpunkt1(): BelongsTo
    {
        return $this->belongsTo(Foerderschwerpunkt::class);
    }

    public function foerderschwerpunkt2(): BelongsTo
    {
        return $this->belongsTo(Foerderschwerpunkt::class);
    }    

    public function lernbereich1note(): BelongsTo
    {
        return $this->belongsTo(Note::class);
    }    

    public function lernbereich2note(): BelongsTo
    {
        return $this->belongsTo(Note::class);
    }

    public function schueler(): BelongsTo
    {
        return $this->belongsTo(Schueler::class);
    }
}
