<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Daten
 *
 * @property int $id
 * @property int $enmRevision
 * @property int $schuljahr
 * @property int $anzahlAbschnitte
 * @property int $aktuellerAbschnitt
 * @property string|null $publicKey
 * @property int $fehlstundenEingabe
 * @property int $fehlstundenSIFachbezogen
 * @property int $fehlstundenSIIFachbezogen
 * @property string $schulform
 * @property string|null $mailadresse
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Lehrer|null $lehrer
 * @method static \Database\Factories\DatenFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Daten newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Daten newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Daten query()
 * @method static \Illuminate\Database\Eloquent\Builder|Daten whereAktuellerAbschnitt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Daten whereAnzahlAbschnitte($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Daten whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Daten whereEnmRevision($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Daten whereFehlstundenEingabe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Daten whereFehlstundenSIFachbezogen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Daten whereFehlstundenSIIFachbezogen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Daten whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Daten whereMailadresse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Daten wherePublicKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Daten whereSchulform($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Daten whereSchuljahr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Daten whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Daten whereUserId($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Schueler[] $schueler
 * @property-read int|null $schueler_count
 * @property int $schulnummer
 * @property int $lehrerID API LehrerID value
 * @method static \Illuminate\Database\Eloquent\Builder|Daten whereLehrerID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Daten whereSchulnummer($value)
 * @property int $lehrer_id User/Lehrer model relation
 */
class Daten extends Model
{
    use HasFactory;

    protected $table = 'daten';

    protected $fillable = [
        'enmRevision',
        'schulnummer',
        'schuljahr',
        'anzahlAbschnitte',
        'aktuellerAbschnitt',
        'publicKey',
        'lehrerID',
        'lerher_id',
        'fehlstundenEingabe',
        'fehlstundenSIFachbezogen',
        'fehlstundenSIIFachbezogen',
        'schulform',
        'mailadresse',
    ];

    public function lehrer(): BelongsTo
    {
        return $this->belongsTo(
			related: Lehrer::class,
			foreignKey: 'lehrer_id',
			ownerKey: 'id',
		);
    }
}
