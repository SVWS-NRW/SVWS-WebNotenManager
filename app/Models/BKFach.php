<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * The `BKFach` class represents a Laravel model for managing remarks associated with BKFacher.
 *
 * @package App\Models
 */
class BKFach extends Model
{
    use HasFactory;

    /**
     * Specify the database table name
     *
     * @var string
     */
    protected $table = 'bkfaecher';

    /**
     * Define the fillable attributes for mass assignment
     *
     * @var string[]
     */
    protected $fillable = [
        'bkabschluss_id', 'fach_id', 'lehrer_id', 'istSchriftlich', 'vornote', 'noteSchriftlichePruefung', 
        'muendlichePruefung', 'muendlichePruefungFreiwillig', 'noteMuendlichePruefung', 'istSchriftlichBerufsabschluss',
        'noteBerufsabschluss', 'abschlussnote',  
    ];

    /**
     * The relation that owns the model
     *
     * @return BelongsTo
     */
    public function abschlussnote(): BelongsTo
    {
        return $this->belongsTo(Note::class);
    }

    /**
     * The relation that owns the model
     *
     * @return BelongsTo
     */
    public function bkabschluss(): BelongsTo
    {
        return $this->belongsTo(BKAbschluss::class);
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

    /**
     * The relation that owns the model
     *
     * @return BelongsTo
     */
    public function lehrer(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The relation that owns the model
     *
     * @return BelongsTo
     */
    public function noteBerufsabschluss(): BelongsTo
    {
        return $this->belongsTo(Note::class);
    }

    /**
     * The relation that owns the model
     *
     * @return BelongsTo
     */
    public function noteMuendlichePruefung(): BelongsTo
    {
        return $this->belongsTo(Note::class);
    }

    /**
     * The relation that owns the model
     *
     * @return BelongsTo
     */
    public function noteSchriftlichePruefung(): BelongsTo
    {
        return $this->belongsTo(Note::class);
    }

    /**
     * The relation that owns the model
     *
     * @return BelongsTo
     */
    public function vornote(): BelongsTo
    {
        return $this->belongsTo(Note::class);
    }
}
