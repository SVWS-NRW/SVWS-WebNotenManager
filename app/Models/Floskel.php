<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Floskel
 *
 * @property int $id
 * @property int $floskelgruppe_id
 * @property string $kuerzel
 * @property string $text
 * @property int|null $fach_id
 * @property int|null $niveau
 * @property int|null $jahrgang_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Fach|null $fach
 * @property-read \App\Models\Floskelgruppe|null $floskelgruppe
 * @property-read \App\Models\Jahrgang|null $jahrgang
 * @method static \Database\Factories\FloskelFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Floskel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Floskel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Floskel query()
 * @method static \Illuminate\Database\Eloquent\Builder|Floskel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Floskel whereFachId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Floskel whereFloskelgruppeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Floskel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Floskel whereJahrgangId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Floskel whereKuerzel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Floskel whereNiveau($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Floskel whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Floskel whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Floskel extends Model
{
    use HasFactory;

    protected $table = 'floskeln';

    protected $fillable = [
        'floskelgruppe_id',
        'kuerzel',
        'text',
        'fach_id',
        'jahrgang_id',
        'niveau',
    ];

	public $timestamps = false;

    public function floskelgruppe(): BelongsTo
    {
        return $this->belongsTo(related: Floskelgruppe::class);
    }    

    public function jahrgang(): BelongsTo
    {
        return $this->belongsTo(related: Jahrgang::class);
    }

    public function fach(): BelongsTo
    {
        return $this->belongsTo(related: Fach::class);
    }
}
