<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
		$this->migrator->add(property: 'filter.mein_unterricht_teilleistungen', value: false);
		$this->migrator->add(property: 'filter.mein_unterricht_mahnungen', value: true);
		$this->migrator->add(property: 'filter.mein_unterricht_fehlstunden', value: false);
		$this->migrator->add(property: 'filter.mein_unterricht_bemerkungen', value: true);

		$this->migrator->add(property: 'filter.leistungdatenuebersicht_teilleistungen', value: false);
		$this->migrator->add(property: 'filter.leistungdatenuebersicht_fachlehrer', value: true);
		$this->migrator->add(property: 'filter.leistungdatenuebersicht_mahnungen', value: false);
		$this->migrator->add(property: 'filter.leistungdatenuebersicht_bemerkungen', value: true);
    }
};