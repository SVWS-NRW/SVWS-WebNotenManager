<?php

namespace App\Models;

use App\Models\User as Lehrer;
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
        'ext_id',   
        'groupable_id',
        'groupable_type',
        'fach_id',
        'kursartID',
        'bezeichnung',
        'bilingualeSprache',
        'wochenstunden',
    ];

    public function groupable(): MorphTo
    {
        return $this->morphTo();
    }

    public function lehrer(): BelongsToMany
    {
        return $this->belongsToMany(Lehrer::class, 'lehrer_lerngruppe');
    }
}
