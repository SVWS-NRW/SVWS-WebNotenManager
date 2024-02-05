<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * The `Klasse` class represents a Laravel model for managing remarks associated with Klassen.
 *
 * @package App\Models
 */
class Klasse extends Model
{
    use HasFactory;

    /**
     * Specify the database table name
     *
     * @var string
     */
    protected $table = 'klassen';

    /**
     * Define the fillable attributes for mass assignment
     *
     * @var string[]
     */
    protected $fillable = [
        'id', 'idJahrgang', 'kuerzel', 'kuerzelAnzeige', 'sortierung', 'editable_teilnoten', 'editable_noten',
		'editable_mahnungen', 'editable_fehlstunden', 'toggleable_fehlstunden', 'editable_fb', 'editable_asv',
		'editable_aue', 'editable_zb',
    ];

    /**
     * The property specifies the type casting for certain attributes in the model.
     *
     * @var string[]
     */
    protected $casts = [
		'editable_teilnoten' => 'boolean',
		'editable_noten' => 'boolean',
		'editable_mahnungen' => 'boolean',
		'editable_fehlstunden' => 'boolean',
		'toggleable_fehlstunden' => 'boolean',
		'editable_fb' => 'boolean',
		'editable_asv' => 'boolean',
		'editable_aue' => 'boolean',
		'editable_zb' => 'boolean',
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
	public function jahrgang(): BelongsTo
	{
		return $this->belongsTo(Jahrgang::class, 'idJahrgang');
	}

    /**
     * The related models that are owned by the model
     *
     * @return HasMany
     */
	public function lerngruppen(): HasMany
	{
		return $this->hasMany(Lerngruppe::class);
	}

    /**
     * The relations that own the model
     *
     * @return BelongsToMany
     */
    public function klassenlehrer(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'klasse_user');
    }

    /**
     * Retrieve a collection of items that do not belong to a specific 'jahrgang' (year) and order them by 'sortierung'.
     *
     * @param string $direction
     * @return Collection
     */
    public static function notBelongingToJahrgangOrdered(string $direction = 'asc'): Collection
	{
		return self::query()
			->whereNull('idJahrgang')
			->orderBy('sortierung', $direction)
			->get();
	}
}
