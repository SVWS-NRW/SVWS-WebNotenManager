<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Zp10
 *
 * @property int $id
 * @property int $schueler_id
 * @property int $fach_id
 * @property \App\Models\Note|null $vornote
 * @property \App\Models\Note|null $noteSchriftlichePruefung
 * @property int $muendlichePruefung
 * @property int $muendlichePruefungFreiwillig
 * @property \App\Models\Note|null $noteMuendlichePruefung
 * @property \App\Models\Note|null $abschlussnote
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Fach|null $fach
 * @property-read \App\Models\Schueler|null $schueler
 * @method static \Database\Factories\Zp10Factory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Zp10 newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Zp10 newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Zp10 query()
 * @method static \Illuminate\Database\Eloquent\Builder|Zp10 whereAbschlussnote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Zp10 whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Zp10 whereFachId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Zp10 whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Zp10 whereMuendlichePruefung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Zp10 whereMuendlichePruefungFreiwillig($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Zp10 whereNoteMuendlichePruefung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Zp10 whereNoteSchriftlichePruefung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Zp10 whereSchuelerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Zp10 whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Zp10 whereVornote($value)
 * @mixin \Eloquent
 */
class Zp10 extends Model
{
    use HasFactory;

    protected $table = 'zp10';

    protected $fillable = [
        'schueler_id',
        'fach_id',
        'vornote',
        'noteSchriftlichePruefung', 
        'muendlichePruefung',
        'muendlichePruefungFreiwillig', 
        'noteMuendlichePruefung',
        'abschlussnote', 
    ];

    public function abschlussnote(): BelongsTo
    {
        return $this->belongsTo(Note::class);
    }   

    public function fach(): BelongsTo
    {
        return $this->belongsTo(Fach::class);
    }

    public function noteMuendlichePruefung(): BelongsTo
    {
        return $this->belongsTo(Note::class);
    }

    public function noteSchriftlichePruefung(): BelongsTo
    {
        return $this->belongsTo(Note::class);
    }

    public function schueler(): BelongsTo
    {
        return $this->belongsTo(Schueler::class);
    }

    public function vornote(): BelongsTo
    {
        return $this->belongsTo(Note::class);
    } 
}