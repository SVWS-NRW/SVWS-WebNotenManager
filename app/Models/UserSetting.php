<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\UserSetting
 *
 * @property int $id
 * @property int $user_id
 * @property object|null $filters_leistungsdatenuebersicht
 * @property object|null $filters_meinunterricht
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Database\Factories\UserSettingFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting whereFiltersLeistungsdatenuebersicht($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting whereFiltersMeinunterricht($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting whereUserId($value)
 * @mixin \Eloquent
 */
class UserSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'filters_leistungsdatenuebersicht',
        'filters_meinunterricht',
    ];

    protected $casts = [
        'filters_leistungsdatenuebersicht' => 'object',
        'filters_meinunterricht' => 'object',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
