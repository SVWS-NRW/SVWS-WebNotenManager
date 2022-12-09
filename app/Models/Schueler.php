<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Models\Schueler
 *
 * @property int $id
 * @property int $daten_id
 * @property int $ext_id
 * @property int $jahrgang_id
 * @property int $klasse_id
 * @property string $nachname
 * @property string $vorname
 * @property string $geschlecht
 * @property \App\Models\Fach|null $bilingualeSprache
 * @property int $istZieldifferent
 * @property int $istDaZFoerderung
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Bemerkung|null $bemerkung
 * @property-read \App\Models\BKAbschluss|null $bkabschluss
 * @property-read \App\Models\Daten|null $daten
 * @property-read \App\Models\Jahrgang|null $jahrgang
 * @property-read \App\Models\Klasse|null $klasse
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Leistung[] $leistungen
 * @property-read int|null $leistungen_count
 * @property-read \App\Models\Lernabschnitt|null $lernabschnitt
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Sprachenfolge[] $sprachenfolgen
 * @property-read int|null $sprachenfolgen_count
 * @property-read \App\Models\Zp10|null $zp10
 * @method static \Database\Factories\SchuelerFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Schueler newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Schueler newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Schueler query()
 * @method static \Illuminate\Database\Eloquent\Builder|Schueler whereBilingualeSprache($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schueler whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schueler whereDatenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schueler whereExtId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schueler whereGeschlecht($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schueler whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schueler whereIstDaZFoerderung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schueler whereIstZieldifferent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schueler whereJahrgangId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schueler whereKlasseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schueler whereNachname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schueler whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schueler whereVorname($value)
 * @mixin \Eloquent
 * @property string|null $asv
 * @property string|null $aue
 * @property string|null $zb
 * @property string|null $lels
 * @property string|null $schulformEmpf
 * @property string|null $individuelleVersetzungsbemerkungen
 * @property string|null $foerderbemerkungen
 * @method static \Illuminate\Database\Eloquent\Builder|Schueler whereAsv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schueler whereAue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schueler whereFoerderbemerkungen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schueler whereIndividuelleVersetzungsbemerkungen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schueler whereLels($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schueler whereSchulformEmpf($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schueler whereZb($value)
 */
class Schueler extends Model
{
    use HasFactory;

	const GENDERS = ['m', 'w', 'd', 'x'];

    protected $table = 'schueler';

    protected $fillable = [
        'id',
        'jahrgang_id',
        'klasse_id',
        'nachname',
        'vorname',
        'geschlecht',
        'bilingualeSprache',
        'istZieldifferent',
        'istDaZFoerderung',
    ];

	public $timestamps = false;

    public function bilingualeSprache(): BelongsTo // TODO: not in json
    {
        return $this->belongsTo(Fach::class);
    }

    public function bemerkung(): HasOne // TODO: not in json
    {
        return $this->hasOne(Bemerkung::class);
    }

    public function bkabschluss(): HasOne // TODO: redo import from hasmany to has one, // TODO: not in json
    {
        return $this->hasOne(BKAbschluss::class);
    }

    public function jahrgang(): BelongsTo
    {
        return $this->belongsTo(Jahrgang::class);
    }

    public function klasse(): BelongsTo
    {
        return $this->belongsTo(Klasse::class);
    }

    public function leistungen(): HasMany
    {
        return $this->hasMany(Leistung::class);
    }

    public function sprachenfolgen(): HasMany // TODO: not in json
    {
        return $this->hasMany(Sprachenfolge::class);
    }

    public function lernabschnitt(): HasOne
    {
        return $this->hasOne(Lernabschnitt::class);
    }

    public function zp10(): HasOne // TODO: check imnport after changing hasmany to hasone // TODO: not in json
    {
        return $this->hasOne(Zp10::class);
    }
}
