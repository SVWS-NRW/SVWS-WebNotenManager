<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Bemerkung
 *
 * @property int $id
 * @property int $schueler_id
 * @property string|null $asv
 * @property string $aue
 * @property string|null $zb
 * @property string|null $lels
 * @property string|null $schulformEmpf
 * @property string|null $individuelleVersetzungsbemerkungen
 * @property string|null $foerderbemerkungen
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Schueler|null $schuler
 * @method static \Database\Factories\BemerkungFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Bemerkung newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bemerkung newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bemerkung query()
 * @method static \Illuminate\Database\Eloquent\Builder|Bemerkung whereAsv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bemerkung whereAue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bemerkung whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bemerkung whereFoerderbemerkungen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bemerkung whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bemerkung whereIndividuelleVersetzungsbemerkungen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bemerkung whereLels($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bemerkung whereSchuelerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bemerkung whereSchulformEmpf($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bemerkung whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bemerkung whereZb($value)
 * @mixin \Eloquent
 * @property-read \App\Models\Schueler|null $schueler
 * @property string|null $ASV
 * @property string $tsASV Timestamp
 * @property string|null $AUE
 * @property string $tsAUE Timestamp
 * @property string|null $ZB
 * @property string $tsZB Timestamp
 * @property string|null $LELS
 * @property string $tsIndividuelleVersetzungsbemerkungen Timestamp
 * @method static \Illuminate\Database\Eloquent\Builder|Bemerkung whereASV($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bemerkung whereAUE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bemerkung whereLELS($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bemerkung whereTsASV($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bemerkung whereTsAUE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bemerkung whereTsIndividuelleVersetzungsbemerkungen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bemerkung whereTsZB($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bemerkung whereZB($value)
 */
class Bemerkung extends Model
{
    use HasFactory;

	const ALLOWED_BEMERKUNGEN = [
		'ASV', 'AUE', 'ZB',
	];

    protected $table = 'bemerkungen';

    protected $fillable = [
        'schueler_id',
        'ASV',
        'tsASV',
        'AUE',
        'tsAUE',
        'ZB',
        'tsZB',
        'LELS',
        'schulformEmpf',
        'individuelleVersetzungsbemerkungen',
        'tsIndividuelleVersetzungsbemerkungen',
        'foerderbemerkungen',
    ];

    public function schueler(): BelongsTo
    {
        return $this->belongsTo(related: Schueler::class);
    }
}
