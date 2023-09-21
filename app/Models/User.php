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
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property string|null $two_factor_confirmed_at
 * @property string|null $remember_token
 * @property int|null $current_team_id
 * @property string|null $profile_photo_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $profile_photo_url
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCurrentTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProfilePhotoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorConfirmedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorRecoveryCodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @property int|null $ext_id
 * @property string $kuerzel
 * @property string $nachname
 * @property string|null $vorname
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Lerngruppe[] $lerngruppen
 * @property-read int|null $lerngruppen_count
 * @method static \Illuminate\Database\Eloquent\Builder|User whereExtId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereKuerzel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereNachname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereVorname($value)
 * @property-read \App\Models\Daten|null $daten
 * @property bool $administrator
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAdministrator($value)
 * @property string $geschlecht
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Klasse[] $klassen
 * @property-read int|null $klassen_count
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGeschlecht($value)
 * @property int $is_administrator
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsAdministrator($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
	use HasApiTokens;
	use HasFactory;
	use HasProfilePhoto;
	use Notifiable;
	use TwoFactorAuthenticatable;

	const GENDERS = ['m', 'w', 'd', 'x'];

    protected $fillable = [
		'id',
		'ext_id',
		'kuerzel',
		'vorname',
		'nachname',
		'geschlecht',
		'email',
		'password',
		'is_administrator',
    ];

    protected $hidden = [
		'password',
		'remember_token',
		'two_factor_recovery_codes',
		'two_factor_secret',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
		'administrator' => 'boolean',
        'settings' => 'object',
    ];

    protected $appends = [
        'profile_photo_url',
    ];

	public function lerngruppen(): BelongsToMany
	{
		return $this->belongsToMany(
			related: Lerngruppe::class,
			table: 'lerngruppe_user'
		);
	}

	public function klassen(): BelongsToMany
	{
		return $this->belongsToMany(
			related: Klasse::class,
			table: 'klasse_user'
		);
	}

	public function daten(): HasOne // TODO
	{
		return $this->hasOne(Daten::class);
	}

	public function isAdministrator(): bool
	{
		return $this->is_administrator;
	}

	public function isLehrer(): bool
	{
		return ! $this->isAdministrator();
	}

    public function settings(): HasOne
    {
        return $this->hasOne(UserSetting::class);
    }
}
