<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
		$this->migrator->add(property: 'matrix.lehrer_can_override_fachlehrer', value: false);
    }
};
