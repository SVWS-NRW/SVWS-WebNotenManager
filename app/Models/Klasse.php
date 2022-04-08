<?php

namespace App\Models;

use App\Models\User as Lehrer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Klasse extends Model
{
    use HasFactory;

    protected $table = 'klassen';

    protected $fillable = [
        'kuerzel',
        'kuerzelAnzeige',
        'sortierung',
    ];

    public function klassenlehrer(): BelongsToMany
    {
        return $this->belongsToMany(Lehrer::class, 'klasse_lehrer');
    }
}
