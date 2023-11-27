<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

  

class AuthCode extends Model

{
    use HasFactory;

    public $table = "2fa_auth_codes";

    protected $fillable = [
        'user_id',
        '2fa_auth_code',
    ];
}