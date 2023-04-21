<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
	public string $name;
	public string $address;
	public string $email;

	public string $management_name;
	public string $management_telephone;
	public string $management_email;

	public string $board_name;
	public string $board_address;
	public string $board_contact;

	public string $gdpr_email;
	public string $gdpr_address;

	public string $hosting_provider_name;
	public string $hosting_provider_address;

	public bool $klassenleitung_fehlstunden_visible;

    public static function group(): string
    {
        return 'general';
    }
}