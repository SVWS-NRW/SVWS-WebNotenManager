<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserLogin extends Model
{
    use HasFactory;

    /**
     * Specify the database table name
     *
     * @var string
     */
    protected $table = 'user_login';

    /**
     * Define the fillable attributes for mass assignment
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id', 'login',
    ];

    /**
     * The property specifies the type casting for certain attributes in the model.
     *
     * @var string[]
     */
    protected $casts = [
        'login' => 'datetime',
    ];

    /**
     * Indicate that the model does not use timestamps.
     *
     * @var bool
     */
	public $timestamps = true;

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
