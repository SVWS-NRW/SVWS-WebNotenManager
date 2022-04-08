<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Teilleistung extends Model
{
    use HasFactory;

    protected $table = 'teilleistungen';

    protected $fillable = [
        'leistung_id',
        'teilleistungsart_id',
        'datum',
        'bemerkung',
        'note_id',
    ];

    public function leistung(): BelongsTo
    {
        return $this->belongsTo(Leistung::class);
    }

    public function teilleistungsart(): BelongsTo
    {
        return $this->belongsTo(Teilleistungsart::class);
    }
}
