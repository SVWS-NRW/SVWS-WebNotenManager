<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Lerngruppe extends Model
{
    use HasFactory;

    protected $table = 'lerngruppen';

    protected $fillable = [        
        'groupable_id',
        'groupable_type',
        'fach_id',
        'kursart_id',
        'bezeichnung',
        'bilingualeSprache',
        'wochenstunden',
    ];

    public function bilingualeSprache(): BelongsTo
    {
        return $this->belongsTo(Fach::class);
    }

    public function groupable(): MorphTo
    {
        return $this->morphTo();
    }

    public function lehrer(): BelongsToMany
    {
        return $this->belongsToMany(Lehrer::class, 'lehrer_lerngruppe');
    }
}
