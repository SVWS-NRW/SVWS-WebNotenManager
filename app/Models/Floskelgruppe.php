<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Floskelgruppe extends Model
{
    use HasFactory;

    protected $table = 'floskelgruppen';

    protected $fillable = [
        'kuerzel',
        'bezeichnung',
        'hauptgruppe',
    ];

    public function floskeln(): HasMany
    {
        return $this->hasMany(Floskel::class);
    }
}
