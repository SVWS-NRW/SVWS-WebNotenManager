<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * The `Floskelgruppe` class represents a Laravel model for managing remarks associated with Floskelgruppen.
 *
 * @package App\Models
 */
class Floskelgruppe extends Model
{
    use HasFactory;

    /**
     * Specify the database table name
     *
     * @var string
     */
    protected $table = 'floskelgruppen';

    /**
     * Define the fillable attributes for mass assignment
     *
     * @var string[]
     */
    protected $fillable = [
        'kuerzel', 'bezeichnung', 'hauptgruppe',
    ];

    /**
     * Indicate that the model does not use timestamps.
     *
     * @var bool
     */
	public $timestamps = false;

    /**
     * The related models that are owned by the model
     *
     * @return HasMany
     */
    public function floskeln(): HasMany
    {
        return $this->hasMany( Floskel::class);
    }
}
