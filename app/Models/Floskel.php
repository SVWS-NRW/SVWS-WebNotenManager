<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Floskel extends Model
{
    use HasFactory;

    protected $table = 'floskeln';

    protected $fillable = [
        'floskelgruppe_id',
        'kuerzel',
        'text',
        'fach_id',
        'niveau',
        'jahrgang_id'
    ];

    public function floskelgruppe(): BelongsTo
    {
        return $this->belongsTo(Floskelgruppe::class);
    }    

    public function jahrgang(): BelongsTo
    {
        return $this->belongsTo(Jahrgang::class);
    }

    public function fach(): BelongsTo
    {
        return $this->belongsTo(Fach::class);
    }
}
