<?php

namespace App\Models;

use App\Models\User as Lehrer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BKFach extends Model
{
    use HasFactory;

    protected $table = 'bkfaecher';

    protected $fillable = [
        'bkabschluss_id', 
        'fach_id', 
        'user_id',
        'istSchriftlich',
        'vornote',  
        'noteSchriftlichePruefung', 
        'muendlichePruefung',
        'muendlichePruefungFreiwillig',
        'noteMuendlichePruefung', 
        'istSchriftlichBerufsabschluss',
        'noteBerufsabschluss',  
        'abschlussnote',  
    ];

    public function abschlussnote(): BelongsTo
    {
        return $this->belongsTo(Note::class);
    }    

    public function bkabschluss(): BelongsTo
    {
        return $this->belongsTo(BKAbschluss::class);
    }    

    public function fach(): BelongsTo
    {
        return $this->belongsTo(Fach::class);
    }

    public function lehrer(): BelongsTo
    {
        return $this->belongsTo(Lehrer::class);
    }

    public function noteBerufsabschluss(): BelongsTo
    {
        return $this->belongsTo(Note::class);
    }

    public function noteMuendlichePruefung(): BelongsTo
    {
        return $this->belongsTo(Note::class);
    }

    public function noteSchriftlichePruefung(): BelongsTo
    {
        return $this->belongsTo(Note::class);
    }

    public function vornote(): BelongsTo
    {
        return $this->belongsTo(Note::class);
    }
}
