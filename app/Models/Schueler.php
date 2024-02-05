<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * The `Schueler` class represents a Laravel model for managing remarks associated with Schueler.
 *
 * @package App\Models
 */
class Schueler extends Model
{
    use HasFactory;

    /*
     * Define a list of allowed genders
     *
     * @return string[]
     */
	const GENDERS = ['m', 'w', 'd', 'x'];

    /**
     * Specify the database table name
     *
     * @var string
     */
    protected $table = 'schueler';

    /**
     * Define the fillable attributes for mass assignment
     *
     * @var string[]
     */
    protected $fillable = [
        'id', 'jahrgang_id', 'klasse_id', 'nachname', 'vorname', 'geschlecht', 'bilingualeSprache', 'istZieldifferent',
        'istDaZFoerderung',
    ];

	public $timestamps = false;

    /**
     * The relation that owns the model
     *
     * @return BelongsTo
     */
    public function bilingualeSprache(): BelongsTo // TODO: not in json
    {
        return $this->belongsTo(Fach::class);
    }

    /**
     * The related model that is owned by the model
     *
     * @return HasOne
     */
    public function bemerkung(): HasOne // TODO: not in json
    {
        return $this->hasOne(Bemerkung::class);
    }

    /**
     * The related model that is owned by the model
     *
     * @return HasOne
     */
    public function bkabschluss(): HasOne // TODO: redo import from hasmany to has one, // TODO: not in json
    {
        return $this->hasOne(BKAbschluss::class);
    }

    /**
     * The relation that owns the model
     *
     * @return BelongsTo
     */
    public function jahrgang(): BelongsTo
    {
        return $this->belongsTo(Jahrgang::class);
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

    /**
     * The related models that are owned by the model
     *
     * @return HasMany
     */
    public function leistungen(): HasMany
    {
        return $this->hasMany(Leistung::class);
    }

    /**
     * The related models that are owned by the model
     *
     * @return HasMany
     */
    public function sprachenfolgen(): HasMany // TODO: not in json
    {
        return $this->hasMany(Sprachenfolge::class);
    }

    /**
     * The related model that is owned by the model
     *
     * @return HasOne
     */
    public function lernabschnitt(): HasOne
    {
        return $this->hasOne(Lernabschnitt::class);
    }

    /**
     * The related model that is owned by the model
     *
     * @return HasOne
     */
    public function zp10(): HasOne // TODO: check imnport after changing hasmany to hasone // TODO: not in json
    {
        return $this->hasOne(Zp10::class);
    }

	public function sharesKlasseWithCurrentUser(): bool
	{
		return in_array($this->klasse_id, Auth::user()->klassen->pluck('id')->toArray());
	}

    /**
     * Retrieve a collection of items with related data for export purposes.
     *
     * @return array
     */
    public static function exportCollection(): array
	{
		return self::with([
            'bemerkung',
            'leistungen' => ['note'],
            'lernabschnitt' => [
                'lernbereich1Note',
                'lernbereich2Note',
                'foerderschwerpunkt1Relation',
                'foerderschwerpunkt2Relation',
            ],
        ])
        ->get();
	}
}
