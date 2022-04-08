<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Zp10 extends Model
{
    use HasFactory;

    protected $table = 'zp10';

    protected $fillable = [
        'schueler_id',
        'fach_id',
        'vornote',
        'noteSchriftlichePruefung', 
        'muendlichePruefung',
        'muendlichePruefungFreiwillig', 
        'noteMuendlichePruefung',
        'abschlussnote', 
    ];

    public function abschlussnote(): BelongsTo
    {
        return $this->belongsTo(Note::class);
    }   

    public function fach(): BelongsTo
    {
        return $this->belongsTo(Fach::class);
    }

    public function noteMuendlichePruefung(): BelongsTo
    {
        return $this->belongsTo(Note::class);
    }

    public function noteSchriftlichePruefung(): BelongsTo
    {
        return $this->belongsTo(Note::class);
    }

    public function schueler(): BelongsTo
    {
        return $this->belongsTo(Schueler::class);
    }

    public function vornote(): BelongsTo
    {
        return $this->belongsTo(Note::class);
    } 
}
