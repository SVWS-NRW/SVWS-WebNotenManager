<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * The `AuthCode` class represents a Laravel model for managing two-factor authentication (2FA) authentication codes.
 *
 * @package App\Models
 */
class AuthCode extends Model
{
    use HasFactory;

    /**
     * Specify the database table name
     *
     * @var string
     */
    public $table = "2fa_auth_codes";

    /**
     * Define the fillable attributes for mass assignment
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id', '2fa_auth_code',
    ];
}