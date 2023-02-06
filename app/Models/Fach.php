<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Fach
 *
 * @property int $id
 * @property int $ext_id
 * @property string $kuerzel
 * @property string $kuerzelAnzeige
 * @property int $sortierung
 * @property int $istFremdsprache
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\FachFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Fach newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Fach newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Fach query()
 * @method static \Illuminate\Database\Eloquent\Builder|Fach whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fach whereExtId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fach whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fach whereIstFremdsprache($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fach whereKuerzel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fach whereKuerzelAnzeige($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fach whereSortierung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fach whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Fach extends Model
{
    use HasFactory;

    protected $table = 'faecher';

    protected $fillable = [
        'id',
        'kuerzel',
        'kuerzelAnzeige',
        'sortierung',
        'istFremdsprache',
    ];

	public $timestamps = false;

	public function floskeln(): HasMany
	{
		return $this->hasMany(related: Floskel::class);
	}
}
