<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Foerderschwerpunkt extends Model
{
    use HasFactory;

    protected $table = 'foerderschwerpunkte';

    protected $fillable = [
        'kuerzel',
        'beschreibung',
    ];
}
