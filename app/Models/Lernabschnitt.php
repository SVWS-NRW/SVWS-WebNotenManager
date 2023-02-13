<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Lernabschnitt
 *
 * @property int $id
 * @property int $ext_id
 * @property int $schueler_id
 * @property string $pruefungsordnung
 * @property \App\Models\Note|null $lernbereich1note
 * @property \App\Models\Note|null $lernbereich2note
 * @property \App\Models\Foerderschwerpunkt|null $foerderschwerpunkt1
 * @property \App\Models\Foerderschwerpunkt|null $foerderschwerpunkt2
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Schueler|null $schueler
 * @method static \Database\Factories\LernabschnittFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Lernabschnitt newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Lernabschnitt newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Lernabschnitt query()
 * @method static \Illuminate\Database\Eloquent\Builder|Lernabschnitt whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lernabschnitt whereExtId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lernabschnitt whereFoerderschwerpunkt1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lernabschnitt whereFoerderschwerpunkt2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lernabschnitt whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lernabschnitt whereLernbereich1note($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lernabschnitt whereLernbereich2note($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lernabschnitt wherePruefungsordnung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lernabschnitt whereSchuelerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lernabschnitt whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Models\Note|null $lernbereich1Note
 * @property-read \App\Models\Note|null $lernbereich2Note
 * @property-read \App\Models\Foerderschwerpunkt|null $foerderschwerpunkt1Relation
 * @property-read \App\Models\Foerderschwerpunkt|null $foerderschwerpunkt2Relation
 * @property int|null $fehlstundenGesamt
 * @property string $tsFehlstundenGesamt Timestamp
 * @property int|null $fehlstundenUnentschuldigt
 * @property string $tsFehlstundenUnentschuldigt Timestamp
 * @method static \Illuminate\Database\Eloquent\Builder|Lernabschnitt whereFehlstundenGesamt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lernabschnitt whereFehlstundenUnentschuldigt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lernabschnitt whereTsFehlstundenGesamt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lernabschnitt whereTsFehlstundenUnentschuldigt($value)
 */
class Lernabschnitt extends Model
{
    use HasFactory;

    protected $table = 'lernabschnitte';

    protected $fillable = [
        'id',
        'schuler_id',
        'fehlstundenGesamt',
        'tsFehlstundenGesamt',
        'fehlstundenUnentschuldigt',
        'tsFehlstundenUnentschuldigt',
        'pruefungsordnung',
        'lernbereich1note',
        'lernbereich2note',
        'foerderschwerpunkt1',
        'foerderschwerpunkt2',
    ];

    public function foerderschwerpunkt1Relation(): BelongsTo
    {
        return $this->belongsTo(
			related: Foerderschwerpunkt::class,
			foreignKey: 'foerderschwerpunkt1',
			ownerKey: 'id',
		);
    }

    public function foerderschwerpunkt2Relation(): BelongsTo
    {
        return $this->belongsTo(
			related: Foerderschwerpunkt::class,
			foreignKey: 'foerderschwerpunkt2',
			ownerKey: 'id',
		);
    }

    public function lernbereich1Note(): BelongsTo
    {
        return $this->belongsTo(
			related: Note::class,
			foreignKey: 'lernbereich1note',
			ownerKey: 'id',
		);
    }

    public function lernbereich2Note(): BelongsTo
    {
        return $this->belongsTo(
			related: Note::class,
			foreignKey: 'lernbereich2note',
			ownerKey: 'id',
		);
    }

    public function schueler(): BelongsTo
    {
        return $this->belongsTo(related: Schueler::class);
    }
}
