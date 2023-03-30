<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Jahrgang
 *
 * @property int $id
 * @property int $ext_id
 * @property string $kuerzel
 * @property string $kuerzelAnzeige
 * @property string $beschreibung
 * @property string $stufe
 * @property int|null $sortierung
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\JahrgangFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Jahrgang newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Jahrgang newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Jahrgang query()
 * @method static \Illuminate\Database\Eloquent\Builder|Jahrgang whereBeschreibung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jahrgang whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jahrgang whereExtId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jahrgang whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jahrgang whereKuerzel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jahrgang whereKuerzelAnzeige($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jahrgang whereSortierung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jahrgang whereStufe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jahrgang whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Jahrgang extends Model
{
    use HasFactory;

    protected $table = 'jahrgaenge';

    protected $fillable = [
        'id',
        'kuerzel',
        'kuerzelAnzeige',
        'beschreibung',
        'stufe',
        'sortierung',
    ];

	public $timestamps = false;

	public function klassen(): HasMany
	{
		return $this->hasMany(related: Klasse::class, foreignKey: 'idJahrgang');
	}

}
