<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * The `Leistung` class represents a Laravel model for managing remarks associated with Leistungen.
 *
 * @package App\Models
 */
class Leistung extends Model
{
    use HasFactory;

    /**
     * Specify the database table name
     *
     * @var string
     */
    protected $table = 'leistungen';

    /**
     * Define the fillable attributes for mass assignment
     *
     * @var string[]
     */
    protected $fillable = [
        'id', 'schueler_id', 'lerngruppe_id', 'note_id', 'tsNote', 'istSchriftlich', 'abiturfach', 'fehlstundenFach',
		'tsFehlstundenFach', 'fehlstundenUnentschuldigtFach', 'tsFehlstundenUnentschuldigtFach', 
        'fachbezogeneBemerkungen', 'tsFachbezogeneBemerkungen', 'neueZuweisungKursart', 'istGemahnt', 'tsIstGemahnt',
        'mahndatum',
    ];
    
    /**
     * The property specifies the type casting for certain attributes in the model.
     *
     * @var string[]
     */
	protected $casts = [
		'mahndatum' => 'datetime',
        'istGemahnt' => 'boolean',
	];

    /**
     * The relation that owns the model
     *
     * @return BelongsTo
     */
    public function lerngruppe(): BelongsTo
    {
        return $this->belongsTo(Lerngruppe::class);
    }

    /**
     * The relation that owns the model
     *
     * @return BelongsTo
     */
    public function note(): BelongsTo
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
     * The related models that are owned by the model
     *
     * @return HasMany
     */
    public function teilleistungen(): HasMany
    {
        return $this->hasMany(Teilleistung::class);
    }

    /**
     * Check if the current user shares the same 'klasse' (class) with this model instance.
     *
     * @return bool
     */
    public function sharesKlasseWithCurrentUser(): bool
	{
		return in_array($this->schueler->klasse_id, Auth::user()->klassen->pluck('id')->toArray());
	}

    /**
     * Check if the current user shares the same 'lerngruppe' (study group) with this model instance.
     *
     * @return bool
     */
    public function sharesLerngruppeWithCurrentUser(): bool
	{
		return in_array($this->lerngruppe_id, Auth::user()->lerngruppen->pluck('id')->toArray());
	}
}
