<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * The `Teilleistung` class represents a Laravel model for managing remarks associated with Teilleistungn.
 *
 * @package App\Models
 */
class Teilleistung extends Model
{
    use HasFactory;

    /**
     * Specify the database table name
     *
     * @var string
     */
    protected $table = 'teilleistungen';

    /**
     * Define the fillable attributes for mass assignment
     *
     * @var string[]
     */
    protected $fillable = [
        'leistung_id', 'teilleistungsart_id', 'datum', 'bemerkung', 'note_id',
    ];

    /**
     * The relation that owns the model
     *
     * @return BelongsTo
     */
    public function leistung(): BelongsTo
    {
        return $this->belongsTo(Leistung::class);
    }

    /**
     * The relation that owns the model
     *
     * @return BelongsTo
     */
    public function teilleistungsart(): BelongsTo
    {
        return $this->belongsTo(Teilleistungsart::class);
    }
}
