<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * The `Lernabschnitt` class represents a Laravel model for managing remarks associated with Lernabschnitte.
 *
 * @package App\Models
 */
class Lernabschnitt extends Model
{
    use HasFactory;

    /**
     * Specify the database table name
     *
     * @var string
     */
    protected $table = 'lernabschnitte';

    /**
     * Define the fillable attributes for mass assignment
     *
     * @var string[]
     */
    protected $fillable = [
        'id', 'schuler_id', 'fehlstundenGesamt', 'tsFehlstundenGesamt', 'fehlstundenGesamtUnentschuldigt',
        'tsFehlstundenGesamtUnentschuldigt', 'pruefungsordnung', 'lernbereich1note', 'lernbereich2note',
        'foerderschwerpunkt1', 'foerderschwerpunkt2',
    ];

    /**
     * The relation that owns the model
     *
     * @return BelongsTo
     */
    public function foerderschwerpunkt1Relation(): BelongsTo
    {
        return $this->belongsTo(Foerderschwerpunkt::class, 'foerderschwerpunkt1', 'id');
    }

    /**
     * The relation that owns the model
     *
     * @return BelongsTo
     */
    public function foerderschwerpunkt2Relation(): BelongsTo
    {
        return $this->belongsTo(Foerderschwerpunkt::class, 'foerderschwerpunkt2', 'id');
    }

    /**
     * The relation that owns the model
     *
     * @return BelongsTo
     */
    public function lernbereich1Note(): BelongsTo
    {
        return $this->belongsTo(Note::class, 'lernbereich1note', 'id');
    }

    /**
     * The relation that owns the model
     *
     * @return BelongsTo
     */
    public function lernbereich2Note(): BelongsTo
    {
        return $this->belongsTo(Note::class, 'lernbereich2note', 'id');
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
