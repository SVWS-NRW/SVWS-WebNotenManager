<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Kurs extends Model
{
    use HasFactory;

    protected $table = 'kurse';

    protected $fillable = [
        'ext_id',
        'kuerzel', 
        // TODO: This complete model is missing in the json provided by the customer. This will be delivered soon.
    ];

    public function lerngruppen(): MorphMany
    {
        return $this->morphMany(Lerngruppe::class, 'groupable');
    }
}
