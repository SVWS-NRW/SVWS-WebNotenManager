<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Floskelgruppe
 *
 * @property int $id
 * @property string $kuerzel
 * @property string $bezeichnung
 * @property string $hauptgruppe
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Floskel[] $floskeln
 * @property-read int|null $floskeln_count
 * @method static \Database\Factories\FloskelgruppeFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Floskelgruppe newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Floskelgruppe newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Floskelgruppe query()
 * @method static \Illuminate\Database\Eloquent\Builder|Floskelgruppe whereBezeichnung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Floskelgruppe whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Floskelgruppe whereHauptgruppe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Floskelgruppe whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Floskelgruppe whereKuerzel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Floskelgruppe whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Floskelgruppe extends Model
{
    use HasFactory;

    protected $table = 'floskelgruppen';

    protected $fillable = [
        'kuerzel',
        'bezeichnung',
        'hauptgruppe',
    ];

	public $timestamps = false;

    public function floskeln(): HasMany
    {
        return $this->hasMany(Floskel::class);
    }
}
