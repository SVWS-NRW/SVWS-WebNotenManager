<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
