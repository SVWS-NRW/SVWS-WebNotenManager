<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * The `Floskel` class represents a Laravel model for managing remarks associated with Floskeln.
 *
 * @package App\Models
 */
class Floskel extends Model
{
    use HasFactory;

    /**
     * Specify the database table name
     *
     * @var string
     */
    protected $table = 'floskeln';

    /**
     * Define the fillable attributes for mass assignment
     *
     * @var string[]
     */
    protected $fillable = [
        'floskelgruppe_id', 'kuerzel', 'text', 'fach_id', 'jahrgang_id', 'niveau',
    ];

    /**
     * Indicate that the model does not use timestamps.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The relation that owns the model
     *
     * @return BelongsTo
     */
    public function floskelgruppe(): BelongsTo
    {
        return $this->belongsTo(Floskelgruppe::class);
    }

    /**
     * The relation that owns the model
     *
     * @return BelongsTo
     */
    public function jahrgang(): BelongsTo
    {
        return $this->belongsTo(Jahrgang::class);
    }

    /**
     * The relation that owns the model
     *
     * @return BelongsTo
     */
    public function fach(): BelongsTo
    {
        return $this->belongsTo(Fach::class);
    }
}
