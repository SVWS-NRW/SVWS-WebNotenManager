<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
 * @property-read \Illuminate\Database\Eloquent\Collection|Lehrer[] $klassenlehrer
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
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Lerngruppe[] $lerngruppen
 * @property-read int|null $lerngruppen_count
 */
class Klasse extends Model
{
    use HasFactory;

    protected $table = 'klassen';

    protected $fillable = [
        'id',
        'kuerzel',
        'kuerzelAnzeige',
        'sortierung',
    ];

	public $timestamps = false;

	public function lerngruppen(): HasMany
	{
		return $this->hasMany(related: Lerngruppe::class);
	}

    public function klassenlehrer(): BelongsToMany
    {
        return $this->belongsToMany(
			related: User::class,
			table: 'klasse_user',
		);
    }
}
