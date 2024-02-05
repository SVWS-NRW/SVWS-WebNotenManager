<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * The `Daten` class represents a Laravel model for managing remarks associated with Daten.
 *
 * @package App\Models
 */
class Daten extends Model
{
    use HasFactory;

    /**
     * Specify the database table name
     *
     * @var string
     */
    protected $table = 'daten';

    /**
     * Define the fillable attributes for mass assignment
     *
     * @var string[]
     */
    protected $fillable = [
        'enmRevision', 'schulnummer', 'schuljahr', 'anzahlAbschnitte', 'aktuellerAbschnitt', 'publicKey', 'lehrerID',
        'lerher_id', 'fehlstundenEingabe', 'fehlstundenSIFachbezogen', 'fehlstundenSIIFachbezogen', 'schulform',
        'mailadresse',
    ];

    /**
     * The relation that owns the model
     *
     * @return BelongsTo
     */
    public function lehrer(): BelongsTo // TODO: CHeck lehrer
    {
        return $this->belongsTo(User::class, 'lehrer_id', 'id');
    }
}
