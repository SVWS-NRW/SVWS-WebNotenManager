<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Teilleistungsart extends Model
{
    use HasFactory;

    protected $table = 'teilleistungsarten';

    protected $fillable = [
        'ext_id',
        'bezeichnung',
        'sortierung',
        'gewichtung',
    ];

    public function teilleistungen(): HasMany
    {
        return $this->hasMany(Teilleistung::class);
    }
}
