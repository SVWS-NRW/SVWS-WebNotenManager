<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class FilterSettings extends Settings
{
	public bool $mein_unterricht_teilleistungen;
	public bool $mein_unterricht_mahnungen;
	public bool $mein_unterricht_fehlstunden;
	public bool $mein_unterricht_bemerkungen;
	public bool $mein_unterricht_kurs;
	public bool $mein_unterricht_note;
	public bool $mein_unterricht_fach;

	public bool $leistungdatenuebersicht_teilleistungen;
	public bool $leistungdatenuebersicht_fachlehrer;
	public bool $leistungdatenuebersicht_mahnungen;
	public bool $leistungdatenuebersicht_bemerkungen;

	public static function group(): string
    {
        return 'filter';
    }
}