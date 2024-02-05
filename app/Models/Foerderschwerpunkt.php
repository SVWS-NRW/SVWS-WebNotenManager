<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * The `Foerderschwerpunkt` class represents a Laravel model for managing remarks associated with Foerderschwerpunkte.
 *
 * @package App\Models
 */
class Foerderschwerpunkt extends Model
{
    use HasFactory;

    /**
     * Specify the database table name
     *
     * @var string
     */
    protected $table = 'foerderschwerpunkte';

    /**
     * Define the fillable attributes for mass assignment
     *
     * @var string[]
     */
    protected $fillable = [
        'id', 'kuerzel', 'beschreibung',
    ];

    /**
     * Indicate that the model does not use timestamps.
     *
     * @var bool
     */
	public $timestamps = false;
}
