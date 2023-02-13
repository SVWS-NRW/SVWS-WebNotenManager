<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

/**
 * App\Models\Lehrer
 *
 * @property int $id
 * @property string $kuerzel
 * @property string $vorname
 * @property string $nachname
 * @property string $geschlecht
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property bool $administrator
 * @property string|null $remember_token
 * @property int|null $current_team_id
 * @property string|null $profile_photo_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Daten|null $daten
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Klasse[] $klassen
 * @property-read int|null $klassen_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Lerngruppe[] $lerngruppen
 * @property-read int|null $lerngruppen_count
 * @method static \Database\Factories\LehrerFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Lehrer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Lehrer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Lehrer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Lehrer whereAdministrator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lehrer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lehrer whereCurrentTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lehrer whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lehrer whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lehrer whereGeschlecht($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lehrer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lehrer whereKuerzel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lehrer whereNachname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lehrer wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lehrer whereProfilePhotoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lehrer whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lehrer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lehrer whereVorname($value)
 * @mixin \Eloquent
 * @property-read string $profile_photo_url
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 */
class Lehrer extends Authenticatable
{
	use HasApiTokens;
	use HasFactory;
	use HasProfilePhoto;
	use Notifiable;
	use TwoFactorAuthenticatable;

	protected $table = 'lehrer';

	const GENDERS = ['m', 'w', 'd', 'x'];

	protected $fillable = [
		'id',
		'kuerzel',
		'vorname',
		'nachname',
		'geschlecht',
		'email',
		'password',
		'administrator',
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
	];

	protected $appends = [
		'profile_photo_url',
	];

	public function lerngruppen(): BelongsToMany
	{
		return $this->belongsToMany(
			related: Lerngruppe::class,
			table: 'lerngruppe_lehrer'
		);
	}

	public function klassen(): BelongsToMany
	{
		return $this->belongsToMany(
			related: Klasse::class,
			table: 'klasse_lehrer'
		);
	}

	public function daten(): HasOne // TODO
	{
		return $this->hasOne(Daten::class);
	}
}
