<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fach extends Model
{
    use HasFactory;

    protected $table = 'faecher';

    protected $fillable = [
        'kuerzel', 
        'kuerzelAnzeige',
        'sortierung',
        'istFremdsprache',
    ];
}
