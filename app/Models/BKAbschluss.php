<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * The `BKAbschluss` class represents a Laravel model for managing remarks associated with BKAbschlüsse.
 *
 * @package App\Models
 */
class BKAbschluss extends Model
{
    use HasFactory;

    /**
     * Specify the database table name
     *
     * @var string
     */
    protected $table = 'bkabschluesse';

    /**
     * Define the fillable attributes for mass assignment
     *
     * @var string[]
     */
    protected $fillable = [
        'schueler_id', 'hatZulassung', 'hatBestanden', 'hatZulassungErweiterteBeruflicheKenntnisse',
        'hatErworbenErweiterteBeruflicheKenntnisse', 'notePraktischePruefung', 'noteKolloqium',
        'hatZulassungBerufsabschlusspruefung', 'hatBestandenBerufsabschlusspruefung', 'themaAbschlussarbeit',
        'istVorhandenBerufsabschlusspruefung', 'noteFachpraxis', 'istFachpraktischerTeilAusreichend',
    ];

    /**
     * The relation that owns the model
     *
     * @return BelongsTo
     */
    public function noteFachpraxis(): BelongsTo
    {
        return $this->belongsTo(Note::class);
    }

    /**
     * The relation that owns the model
     *
     * @return BelongsTo
     */
    public function noteKolloqium(): BelongsTo
    {
        return $this->belongsTo(Note::class);
    }

    /**
     * The relation that owns the model
     *
     * @return BelongsTo
     */
    public function notePraktischePruefung(): BelongsTo
    {
        return $this->belongsTo(Note::class);
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

    /**
     * The related models that are owned by the model
     *
     * @return HasMany
     */
    public function bkFaecher(): HasMany
    {
        return $this->hasMany(BKFach::class);
    }
}
