<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

/**
 * The `User` class represents a Laravel model for managing remarks associated with Users.
 *
 * @package App\Models
 */
class User extends Authenticatable
{
	use HasApiTokens;
	use HasFactory;
	use HasProfilePhoto;
	use Notifiable;
	use TwoFactorAuthenticatable;

    /*
     * Define a list of allowed genders
     *
     * @return string[]
     */
	const GENDERS = ['m', 'w', 'd', 'x'];

    /**
     * Define the fillable attributes for mass assignment
     *
     * @var string[]
     */
    protected $fillable = [
		'id', 'ext_id', 'kuerzel', 'vorname', 'nachname', 'geschlecht', 'email', 'password', 'is_administrator',
    ];

    /**
     * Attributes listed here will be hidden when the model is converted to an array or JSON response,
     *
     * @var string[]
     */
    protected $hidden = [
		'password',
		'remember_token',
		'two_factor_recovery_codes',
		'two_factor_secret',
    ];

    /**
     * The property specifies the type casting for certain attributes in the model.
     *
     * @var string[]
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
		'administrator' => 'boolean',
        'settings' => 'object',
    ];

    /**
     * Defines additional computed attributes that are appended to the model's array or JSON representation,
     *
     * @var string[]
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * The relations that own the model
     *
     * @return BelongsToMany
     */
	public function lerngruppen(): BelongsToMany
	{
		return $this->belongsToMany(Lerngruppe::class, 'lerngruppe_user');
	}

    /**
     * The relations that own the model
     *
     * @return BelongsToMany
     */
	public function klassen(): BelongsToMany
	{
		return $this->belongsToMany(Klasse::class, 'klasse_user');
	}

    /**
     * The related model that is owned by the model
     *
     * @return HasOne
     */
	public function daten(): HasOne // TODO Karol
	{
		return $this->hasOne(Daten::class);
	}

    /**
     * Determine whether user is an administrator
     *
     * @return bool
     */
    public function isAdministrator(): bool
	{
		return $this->is_administrator;
	}

    /**
     * Determine whether user is an administrator
     *
     * @return bool
     */
	public function isLehrer(): bool
	{
		return ! $this->isAdministrator();
	}

    /**
     * The related model that is owned by the model
     *
     * @return HasOne
     */
    public function userSettings(): HasOne
    {
        return $this->hasOne(UserSetting::class);
    }

    /**
     * Retrieve filters for a specific column from user settings or configuration.
     *
     * @param string $column
     * @return array
     */
    public function filters(string $column): array
    {
        $filterColumn = "filters_{$column}";

        return $this->userSettings()->exists()
            ? json_decode(json_encode($this->userSettings->$filterColumn), true)
            : config("wenom.filters.{$column}");
    }
}
