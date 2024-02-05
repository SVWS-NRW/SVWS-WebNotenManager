<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * The `Jahrgang` class represents a Laravel model for managing remarks associated with Jahrgange.
 *
 * @package App\Models
 */
class Jahrgang extends Model
{
    use HasFactory;

    /**
     * Specify the database table name
     *
     * @var string
     */
    protected $table = 'jahrgaenge';

    /**
     * Define the fillable attributes for mass assignment
     *
     * @var string[]
     */
    protected $fillable = [
        'id', 'kuerzel', 'kuerzelAnzeige', 'beschreibung', 'stufe', 'sortierung',
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
	public function klassen(): HasMany
	{
		return $this->hasMany(Klasse::class, 'idJahrgang');
	}

    /**
     * Retrieve a collection of items ordered by 'sortierung' field and eager load related 'klassen' with sorting.
     *
     * @param string $direction
     * @return Collection
     */
    public static function orderedWithKlassenOrdered(string $direction = 'asc'): Collection
	{
		return self::query()
			->whereHas('klassen')
			->with('klassen', fn (HasMany $klassen): HasMany =>
				$klassen->orderBy('sortierung', $direction)
			)
			->orderBy('sortierung', $direction)
			->get();
	}

}
