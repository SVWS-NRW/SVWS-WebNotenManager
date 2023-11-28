<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

  

/**
 * App\Models\AuthCode
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $2fa_auth_code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AuthCode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AuthCode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AuthCode query()
 * @method static \Illuminate\Database\Eloquent\Builder|AuthCode where2faAuthCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuthCode whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuthCode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuthCode whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuthCode whereUserId($value)
 * @mixin \Eloquent
 */
class AuthCode extends Model

{
    use HasFactory;

    public $table = "2fa_auth_codes";

    protected $fillable = [
        'user_id',
        '2fa_auth_code',
    ];
}