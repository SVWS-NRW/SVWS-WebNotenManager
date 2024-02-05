<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * The `UserSetting` class represents a Laravel model for managing remarks associated with UserSettings.
 *
 * @package App\Models
 */
class UserSetting extends Model
{
    use HasFactory;

    /**
     * Define the fillable attributes for mass assignment
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id', 'filters_leistungsdatenuebersicht', 'filters_meinunterricht',
    ];

    /**
     * The property specifies the type casting for certain attributes in the model.
     *
     * @var string[]
     */
    protected $casts = [
        'filters_leistungsdatenuebersicht' => 'object',
        'filters_meinunterricht' => 'object',
    ];

    /**
     * The relation that owns the model
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
