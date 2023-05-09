<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * App\Models\Lerngruppe
 *
 * @property int $id
 * @property int $ext_id
 * @property int $groupable_id
 * @property string $groupable_type
 * @property int $fach_id
 * @property string|null $kursartID
 * @property string $bezeichnung
 * @property string|null $bilingualeSprache
 * @property int $wochenstunden
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $groupable
 * @property-read \Illuminate\Database\Eloquent\Collection|Lehrer[] $lehrer
 * @property-read int|null $lehrer_count
 * @method static \Database\Factories\LerngruppeFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Lerngruppe newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Lerngruppe newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Lerngruppe query()
 * @method static \Illuminate\Database\Eloquent\Builder|Lerngruppe whereBezeichnung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lerngruppe whereBilingualeSprache($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lerngruppe whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lerngruppe whereExtId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lerngruppe whereFachId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lerngruppe whereGroupableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lerngruppe whereGroupableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lerngruppe whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lerngruppe whereKursartID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lerngruppe whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lerngruppe whereWochenstunden($value)
 * @property-read Fach|null $fach
 * @property string|null $kursartKuerzel
 * @method static \Illuminate\Database\Eloquent\Builder|Lerngruppe whereKursartKuerzel($value)
 * @property int|null $klasse_id
 * @property string $kID
 * @property-read \App\Models\Klasse|null $klasse
 * @method static \Illuminate\Database\Eloquent\Builder|Lerngruppe whereKID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lerngruppe whereKlasseId($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $user
 * @property-read int|null $user_count
 * @mixin \Eloquent
 */
class Lerngruppe extends Model
{
    use HasFactory;

    protected $table = 'lerngruppen';

    protected $fillable = [
        'id',
        'klasse_id',
        'fach_id',
		'kID',
		'kursartID',
        'bezeichnung',
        'kursartKuerzel',
        'bilingualeSprache',
        'wochenstunden',
    ];

    public function lehrer(): BelongsToMany
    {
        return $this->belongsToMany(
			related: User::class,
			table: 'lerngruppe_user',
		);
    }

    public function fach(): BelongsTo
    {
        return $this->belongsTo(related: Fach::class);
    }

    public function klasse(): BelongsTo
    {
        return $this->belongsTo(related: Klasse::class);
    }
}
