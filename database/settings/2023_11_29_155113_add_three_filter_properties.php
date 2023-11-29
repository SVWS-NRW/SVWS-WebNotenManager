<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add(property: 'filter.mein_unterricht_kurs', value: true);
        $this->migrator->add(property: 'filter.mein_unterricht_note', value: true);
        $this->migrator->add(property: 'filter.mein_unterricht_fach', value: true);
    }
};
