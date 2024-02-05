<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * The `Fach` class represents a Laravel model for managing remarks associated with Faecher.
 *
 * @package App\Models
 */
class Fach extends Model
{
    use HasFactory;

    /**
     * Specify the database table name
     *
     * @var string
     */
    protected $table = 'faecher';

    /**
     * Define the fillable attributes for mass assignment
     *
     * @var string[]
     */
    protected $fillable = [
        'id', 'kuerzel', 'kuerzelAnzeige', 'sortierung', 'istFremdsprache',
    ];

	public $timestamps = false;

    /**
     * The related models that are owned by the model
     *
     * @return HasMany
     */
	public function floskeln(): HasMany
	{
		return $this->hasMany(Floskel::class);
	}
}
