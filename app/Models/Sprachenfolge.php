<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * The `Sprachenfolge` class represents a Laravel model for managing remarks associated with Sprachenfolgen.
 *
 * @package App\Models
 */
class Sprachenfolge extends Model
{
    use HasFactory;

    /**
     * Specify the database table name
     *
     * @var string
     */
    protected $table = 'sprachenfolge';

    /**
     * Define the fillable attributes for mass assignment
     *
     * @var string[]
     */
    protected $fillable = [     // TODO: Check spelling of schueler
        'schuler_id', 'sprache', 'fach_id', 'reihenfolge', 'belegungVonJahrgang', 'belegungVonAbschnitt',
        'belegungBisJahrgang', 'belegungBisAbschnitt', 'referenzniveau', 'belegungSekI',
    ];

    /**
     * The relation that owns the model
     *
     * @return BelongsTo
     */
    public function fach(): BelongsTo
    {
        return $this->belongsTo(Fach::class);
    }

    /**
     * The relation that owns the model
     *
     * @return BelongsTo
     */
    public function schueler(): BelongsTo
    {
        return $this->belongsTo(Schueler::class);
    }
}
