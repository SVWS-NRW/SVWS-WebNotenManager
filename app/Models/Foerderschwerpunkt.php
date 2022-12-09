<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Foerderschwerpunkt
 *
 * @property int $id
 * @property string $kuerzel
 * @property string $beschreibung
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\FoerderschwerpunktFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Foerderschwerpunkt newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Foerderschwerpunkt newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Foerderschwerpunkt query()
 * @method static \Illuminate\Database\Eloquent\Builder|Foerderschwerpunkt whereBeschreibung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Foerderschwerpunkt whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Foerderschwerpunkt whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Foerderschwerpunkt whereKuerzel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Foerderschwerpunkt whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Foerderschwerpunkt extends Model
{
    use HasFactory;

    protected $table = 'foerderschwerpunkte';

    protected $fillable = [
        'id',
        'kuerzel',
        'beschreibung',
    ];

	public $timestamps = false;
}
