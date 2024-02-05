<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * The `Lerngruppe` class represents a Laravel model for managing remarks associated with Lerngruppen.
 *
 * @package App\Models
 */
class Lerngruppe extends Model
{
    use HasFactory;

    /**
     * Specify the database table name
     *
     * @var string
     */
    protected $table = 'lerngruppen';

    /**
     * Define the fillable attributes for mass assignment
     *
     * @var string[]
     */
    protected $fillable = [
        'id', 'klasse_id', 'fach_id', 'kID', 'kursartID', 'bezeichnung', 'kursartKuerzel', 'bilingualeSprache',
        'wochenstunden',
    ];

    /**
     * The relations that own the model
     *
     * @return BelongsToMany
     */
    public function lehrer(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'lerngruppe_user');
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
    public function klasse(): BelongsTo
    {
        return $this->belongsTo(Klasse::class);
    }
}
