<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Zp10 extends Model
{
    use HasFactory;

    /**
     * Specify the database table name
     *
     * @var string
     */
    protected $table = 'zp10';

    /**
     * Define the fillable attributes for mass assignment
     *
     * @var string[]
     */
    protected $fillable = [
        'schueler_id', 'fach_id', 'vornote', 'noteSchriftlichePruefung', 'muendlichePruefung', 
        'muendlichePruefungFreiwillig', 'noteMuendlichePruefung', 'abschlussnote', 
    ];

    /**
     * The relation that owns the model
     *
     * @return BelongsTo
     */
    public function abschlussnote(): BelongsTo
    {
        return $this->belongsTo(Note::class);
    }

    /**
     * The relation that owns the model
     *
     * @return BelongsTo
     */
    public function fach(): BelongsTo
    {
        return $this->belongsTo(Fach::class);
    }

    /**
     * The relation that owns the model
     *
     * @return BelongsTo
     */
    public function noteMuendlichePruefung(): BelongsTo
    {
        return $this->belongsTo(Note::class);
    }

    /**
     * The relation that owns the model
     *
     * @return BelongsTo
     */
    public function noteSchriftlichePruefung(): BelongsTo
    {
        return $this->belongsTo(Note::class);
    }

    /**
     * The relation that owns the model
     *
     * @return BelongsTo
     */
    public function schueler(): BelongsTo
    {
        return $this->belongsTo(Schueler::class);
    }

    /**
     * The relation that owns the model
     *
     * @return BelongsTo
     */
    public function vornote(): BelongsTo
    {
        return $this->belongsTo(Note::class);
    } 
}
