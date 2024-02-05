<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * The `Note` class represents a Laravel model for managing remarks associated with Noten.
 *
 * @package App\Models
 */
class Note extends Model
{
    use HasFactory;

    /**
     * Specify the database table name
     *
     * @var string
     */
    protected $table = 'noten';

    /**
     * Define the fillable attributes for mass assignment
     *
     * @var string[]
     */
    protected $fillable = [
        'id', 'kuerzel', 'notenpunkte', 'text',
    ];

    /**
     * Indicate that the model does not use timestamps.
     *
     * @var bool
     */
	public $timestamps = false;
}
