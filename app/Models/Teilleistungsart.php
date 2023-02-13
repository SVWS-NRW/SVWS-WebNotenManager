<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Teilleistungsart
 *
 * @property int $id
 * @property int $ext_id
 * @property string $bezeichnung
 * @property int|null $sortierung
 * @property float|null $gewichtung
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Teilleistung[] $teilleistungen
 * @property-read int|null $teilleistungen_count
 * @method static \Database\Factories\TeilleistungsartFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistungsart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistungsart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistungsart query()
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistungsart whereBezeichnung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistungsart whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistungsart whereExtId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistungsart whereGewichtung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistungsart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistungsart whereSortierung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistungsart whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Teilleistungsart extends Model
{
    use HasFactory;

    protected $table = 'teilleistungsarten';

    protected $fillable = [
        'bezeichnung',
        'sortierung',
        'gewichtung',
    ];

    public function teilleistungen(): HasMany
    {
        return $this->hasMany(related: Teilleistung::class);
    }
}
