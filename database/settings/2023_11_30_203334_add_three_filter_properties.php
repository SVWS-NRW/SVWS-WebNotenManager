<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add(property: 'filter.leistungdatenuebersicht_kurs', value: true);
        $this->migrator->add(property: 'filter.leistungdatenuebersicht_note', value: true);
        $this->migrator->add(property: 'filter.leistungdatenuebersicht_fach', value: true);
    }
};