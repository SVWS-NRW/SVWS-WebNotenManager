<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\LeistungNormalized
 *
 * @property int $id
 * @property int $leistung_id
 * @property string|null $klasse
 * @property string $vorname
 * @property string $nachname
 * @property string $geschlecht
 * @property string $jahrgang
 * @property string|null $fach
 * @property string $lehrer
 * @property string|null $kurs
 * @property string|null $note
 * @property bool $istGemahnt
 * @property \Illuminate\Support\Carbon|null $mahndatum
 * @property int $fs
 * @property int $ufs
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Leistung|null $leistung
 * @method static \Database\Factories\LeistungNormalizedFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|LeistungNormalized newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LeistungNormalized newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LeistungNormalized query()
 * @method static \Illuminate\Database\Eloquent\Builder|LeistungNormalized whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeistungNormalized whereFach($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeistungNormalized whereFs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeistungNormalized whereGeschlecht($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeistungNormalized whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeistungNormalized whereIstGemahnt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeistungNormalized whereJahrgang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeistungNormalized whereKlasse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeistungNormalized whereKurs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeistungNormalized whereLehrer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeistungNormalized whereLeistungId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeistungNormalized whereMahndatum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeistungNormalized whereNachname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeistungNormalized whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeistungNormalized whereUfs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeistungNormalized whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeistungNormalized whereVorname($value)
 * @mixin \Eloquent
 */
class LeistungNormalized extends Model
{
    use HasFactory;

	protected $table = 'leistung_normalized';

	protected $fillable = [
		'leistung_id',
		'lerngruppe_id',
		'klasse',
		'vorname',
		'nachname',
		'geschlecht',
		'jahrgang',
		'fach',
		'lehrer',
		'kurs',
		'note',
        'istGemahnt',
        'mahndatum',
		'fs',
		'ufs',
		'fachbezogeneBemerkungen',
	];

	protected $casts = [
		'istGemahnt' => 'bool',
		'mahndatum' => 'datetime',
	];

	public function leistung(): BelongsTo
	{
		return $this->belongsTo(Leistung::class);
	}
}
