<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
	public array $settings = [
		['type' => 'school', 'key' => 'school_name', 'value' => '[Name der Schule]'],
		['type' => 'school', 'key' => 'school_address', 'value' => '[Adresse der Schule]'],
		['type' => 'school', 'key' => 'school_email', 'value' => '[E-Mail Adresse der Schule]'],
		['type' => 'school', 'key' => 'school_management_name', 'value' => '[Name Schulleitung]'],
		['type' => 'school', 'key' => 'school_management_telephone', 'value' => '[Sekretariat]'],
		['type' => 'school', 'key' => 'school_management_email', 'value' => '[E-Mail Adresse]'],
		['type' => 'school', 'key' => 'school_board_name', 'value' => '[Name des Schulträgers]'],
		['type' => 'school', 'key' => 'school_board_address', 'value' => '[Anschrift des Schulträgers]'],
		['type' => 'school', 'key' => 'school_board_contact', 'value' => '[Kontaktdaten des Schulträgers]'],
		['type' => 'school', 'key' => 'school_gdpr_email', 'value' => '[Email des Datenschutzbeauftragten]'],
		['type' => 'school', 'key' => 'school_gdpr_address', 'value' => '[Anschrift des Datenschutzbeauftragten]'],
		['type' => 'school', 'key' => 'hosting_provider_name', 'value' => '[Name des Hosters]'],
		['type' => 'school', 'key' => 'hosting_provider_address', 'value' => '[Anschrift des Hosters]'],
		['type' => 'school', 'key' => 'lehrer_can_override_note', 'value' => false],
		['type' => 'school', 'key' => 'klassenleitung_fehlstunden_visible', 'value' => true],

		['type' => 'filter', 'key' => 'mein_unterricht_teilleistungen', 'value' => false],
		['type' => 'filter', 'key' => 'mein_unterricht_mahnungen', 'value' => true],
		['type' => 'filter', 'key' => 'mein_unterricht_fehlstunden', 'value' => false],
		['type' => 'filter', 'key' => 'mein_unterricht_bemerkungen', 'value' => true],

		['type' => 'filter', 'key' => 'leistungdatenuebersicht_teilleistungen', 'value' => false],
		['type' => 'filter', 'key' => 'leistungdatenuebersicht_fachlehrer', 'value' => true],
		['type' => 'filter', 'key' => 'leistungdatenuebersicht_mahnungen', 'value' => false],
		['type' => 'filter', 'key' => 'leistungdatenuebersicht_bemerkungen', 'value' => true],
	];

    public function run(): void
    {
        collect(value: $this->settings)->each(
			callback: fn (array $setting): Setting => Setting::create(attributes: $setting)
		);
    }
}
