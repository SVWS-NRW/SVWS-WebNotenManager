<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Models\Leistung
 *
 * @property int $id
 * @property int $schueler_id
 * @property int $lerngruppe_id
 * @property int|null $note_id
 * @property int $istSchriftlich
 * @property string|null $abiturfach
 * @property int|null $fehlstundenGesamt
 * @property int|null $fehlstundenUnentschuldigt
 * @property string|null $fachbezogeneBemerkungen
 * @property string|null $neueZuweisungKursart
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Lerngruppe|null $lerngruppe
 * @property-read \App\Models\Note|null $note
 * @property-read \App\Models\Schueler|null $schueler
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Teilleistung[] $teilleistungen
 * @property-read int|null $teilleistungen_count
 * @method static \Database\Factories\LeistungFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Leistung newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Leistung newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Leistung query()
 * @method static \Illuminate\Database\Eloquent\Builder|Leistung whereAbiturfach($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leistung whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leistung whereFachbezogeneBemerkungen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leistung whereFehlstundenGesamt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leistung whereFehlstundenUnentschuldigt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leistung whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leistung whereIstSchriftlich($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leistung whereLerngruppeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leistung whereNeueZuweisungKursart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leistung whereNoteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leistung whereSchuelerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leistung whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $ext_id
 * @method static \Illuminate\Database\Eloquent\Builder|Leistung whereExtId($value)
 * @property int $istGemahnt
 * @property \Illuminate\Support\Carbon|null $mahndatum
 * @property-read \App\Models\LeistungNormalized|null $leistungNormalized
 * @method static \Illuminate\Database\Eloquent\Builder|Leistung whereIstGemahnt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leistung whereMahndatum($value)
 */
class Leistung extends Model
{
    use HasFactory;

    protected $table = 'leistungen';

    protected $fillable = [
        'schueler_id',
        'lerngruppe_id',
        'note_id',
        'istSchriftlich',
        'abiturfach',
        'fehlstundenGesamt',
        'fehlstundenUnentschuldigt',
        'fachbezogeneBemerkungen',
        'neueZuweisungKursart',
        'istGemahnt',
        'mahndatum',
    ];

	protected $casts = [
		'mahndatum' => 'datetime',
	];

    public function lerngruppe(): BelongsTo
    {
        return $this->belongsTo(Lerngruppe::class);
    }

    public function note(): BelongsTo
    {
        return $this->belongsTo(Note::class);
    }

    public function schueler(): BelongsTo
    {
        return $this->belongsTo(Schueler::class);
    }

    public function teilleistungen(): HasMany
    {
        return $this->hasMany(Teilleistung::class);
    }

    public function leistungNormalized(): HasOne
    {
        return $this->hasOne(LeistungNormalized::class);
    }
}