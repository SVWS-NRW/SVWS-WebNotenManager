<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bemerkung extends Model
{
    use HasFactory;   
    
    protected $table = 'bemerkungen';

    protected $fillable = [
        'schueler_id', 
        'asv', 
        'aue', 
        'zb', 
        'lels', 
        'schulformEmpf', 
        'individuelleVersetzungsbemerkungen', 
        'foerderbemerkungen', 
    ];

    public function schuler(): BelongsTo
    {
        return $this->belongsTo(Schueler::class);
    }  
}
