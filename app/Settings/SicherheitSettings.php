<?php

//TODO: check if this file is somehow necessary or should be removed

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class SicherheitSettings extends Settings
{
    public bool $zwei_faktor_authentisierung;

    public static function group(): string
    {
        return 'sicherheit';
    }
}

