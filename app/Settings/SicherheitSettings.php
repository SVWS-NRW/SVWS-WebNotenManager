<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class FilterSettings extends Settings
{
	public bool $zwei_faktor_authentisierung;

	public static function group(): string
    {
        return 'sicherheit';
    }
}