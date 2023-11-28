<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Klasse
 *
 * @property int $id
 * @property int $ext_id
 * @property string $kuerzel
 * @property string $kuerzelAnzeige
 * @property int|null $sortierung
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Collection|Lehrer[] $klassenlehrer
 * @property-read int|null $klassenlehrer_count
 * @method static \Database\Factories\KlasseFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Klasse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Klasse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Klasse query()
 * @method static \Illuminate\Database\Eloquent\Builder|Klasse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Klasse whereExtId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Klasse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Klasse whereKuerzel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Klasse whereKuerzelAnzeige($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Klasse whereSortierung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Klasse whereUpdatedAt($value)
 * @property-read Collection|\App\Models\Lerngruppe[] $lerngruppen
 * @property-read int|null $lerngruppen_count
 * @property int|null $idJahrgang
 * @property bool $editable_teilnoten
 * @property bool $editable_noten
 * @property bool $editable_mahnungen
 * @property bool $editable_fehlstunden
 * @property bool $editable_fb
 * @property bool $editable_asv
 * @property bool $editable_aue
 * @property bool $editable_zb
 * @method static \Illuminate\Database\Eloquent\Builder|Klasse whereEditableAsv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Klasse whereEditableAue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Klasse whereEditableFb($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Klasse whereEditableFehlstunden($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Klasse whereEditableMahnungen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Klasse whereEditableNoten($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Klasse whereEditableTeilnoten($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Klasse whereEditableZb($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Klasse whereIdJahrgang($value)
 * @property bool $toggleable_fehlstunden
 * @method static \Illuminate\Database\Eloquent\Builder|Klasse whereToggleableFehlstunden($value)
 * @property-read \App\Models\Jahrgang|null $jahrgang
 * @mixin \Eloquent
 */
class Klasse extends Model
{
    use HasFactory;

    protected $table = 'klassen';

    protected $fillable = [
        'id',
		'idJahrgang',
        'kuerzel',
        'kuerzelAnzeige',
        'sortierung',
		'editable_teilnoten',
		'editable_noten',
		'editable_mahnungen',
		'editable_fehlstunden',
		'toggleable_fehlstunden',
		'editable_fb',
		'editable_asv',
		'editable_aue',
		'editable_zb',
    ];

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

	public $timestamps = false;



	public function jahrgang(): BelongsTo
	{
		return $this->belongsTo(Jahrgang::class, 'idJahrgang');
	}

	public function lerngruppen(): HasMany
	{
		return $this->hasMany(Lerngruppe::class);
	}

    public function klassenlehrer(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'klasse_user');
    }

	public static function notBelongingToJahrgangOrdered(string $direction = 'asc'): Collection
	{
		return self::query()
			->whereNull(columns: 'idJahrgang')
			->orderBy(column: 'sortierung', direction: $direction)
			->get();
	}
}
