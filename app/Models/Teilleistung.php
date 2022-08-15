<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Teilleistung
 *
 * @property int $id
 * @property int $leistung_id
 * @property int $teilleistungsart_id
 * @property string|null $datum
 * @property string|null $bemerkung
 * @property int|null $note_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Leistung|null $leistung
 * @property-read \App\Models\Teilleistungsart|null $teilleistungsart
 * @method static \Database\Factories\TeilleistungFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistung newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistung newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistung query()
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistung whereBemerkung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistung whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistung whereDatum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistung whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistung whereLeistungId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistung whereNoteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistung whereTeilleistungsartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistung whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Teilleistung extends Model
{
    use HasFactory;

    protected $table = 'teilleistungen';

    protected $fillable = [
        'ext_id',
        'leistung_id',
        'teilleistungsart_id',
        'datum',
        'bemerkung',
        'note_id',
    ];

    public function leistung(): BelongsTo
    {
        return $this->belongsTo(Leistung::class);
    }

    public function teilleistungsart(): BelongsTo
    {
        return $this->belongsTo(Teilleistungsart::class);
    }
}
