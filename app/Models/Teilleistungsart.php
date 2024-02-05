<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * The `Teilleistungsart` class represents a Laravel model for managing remarks associated with Teilleistungsarten.
 *
 * @package App\Models
 */
class Teilleistungsart extends Model
{
    use HasFactory;

    /**
     * Specify the database table name
     *
     * @var string
     */
    protected $table = 'teilleistungsarten';

    /**
     * Define the fillable attributes for mass assignment
     *
     * @var string[]
     */
    protected $fillable = [
        'bezeichnung', 'sortierung', 'gewichtung',
    ];

    /**
     * The related models that are owned by the model
     *
     * @return HasMany
     */
    public function teilleistungen(): HasMany
    {
        return $this->hasMany(Teilleistung::class);
    }
}
