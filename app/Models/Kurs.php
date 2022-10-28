<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * App\Models\Kurs
 *
 * @property int $id
 * @property int $ext_id
 * @property string $kuerzel
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Lerngruppe[] $lerngruppen
 * @property-read int|null $lerngruppen_count
 * @method static \Database\Factories\KursFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Kurs newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Kurs newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Kurs query()
 * @method static \Illuminate\Database\Eloquent\Builder|Kurs whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kurs whereExtId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kurs whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kurs whereKuerzel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kurs whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $bezeichnung
 * @method static \Illuminate\Database\Eloquent\Builder|Kurs whereBezeichnung($value)
 */
class Kurs extends Model
{
    use HasFactory;

    protected $table = 'kurse';

    protected $fillable = [
        'ext_id',
        'bezeichnung',
        'kuerzel',
    ];

    public function lerngruppen(): MorphMany
    {
        return $this->morphMany(Lerngruppe::class, 'groupable');
    }
}
