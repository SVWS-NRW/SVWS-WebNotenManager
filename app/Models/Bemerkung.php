<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * The `Bemerkung` class represents a Laravel model for managing remarks associated with students.
 *
 * @package App\Models
 */
class Bemerkung extends Model
{
    use HasFactory;

    /*
     * Define a list of allowed bemerkungen
     *
     * @return string[]
     */
    const ALLOWED_BEMERKUNGEN = [
		'ASV', 'AUE', 'ZB',
	];

    /**
     * Specify the database table name
     *
     * @var string
     */
    protected $table = 'bemerkungen';

    /**
     * Define the fillable attributes for mass assignment
     *
     * @var string[]
     */
    protected $fillable = [
        'schueler_id', 'ASV', 'tsASV', 'AUE', 'tsAUE', 'ZB', 'tsZB', 'LELS', 'schulformEmpf',
        'individuelleVersetzungsbemerkungen', 'tsIndividuelleVersetzungsbemerkungen', 'foerderbemerkungen',
    ];

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
