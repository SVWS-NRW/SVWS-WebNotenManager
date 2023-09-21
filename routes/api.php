<?php

use App\Http\Controllers\AesController;
use App\Http\Controllers\Api\FachbezogeneBemerkung;
use App\Http\Controllers\Api\FachbezogeneFloskeln;
use App\Http\Controllers\Api\Fehlstunden;
use App\Http\Controllers\Api\Floskeln;
use App\Http\Controllers\Api\Klassenleitung;
use App\Http\Controllers\Api\Leistungsdatenuebersicht;
use App\Http\Controllers\Api\Mahnungen;
use App\Http\Controllers\Api\MeinUnterricht;
use App\Http\Controllers\Api\Noten;
use App\Http\Controllers\Api\SchuelerBemerkung;
use App\Http\Controllers\Api\Settings\EnvController;
use App\Http\Controllers\Api\Settings\MatrixController;
use App\Http\Controllers\Api\Settings\SettingsController;
use App\Http\Controllers\Api\Settings\UserSettingsController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\SecureTransferController;
use App\Services\DataImportService;
use Illuminate\Support\Facades\Route;

Route::controller(SecureTransferController::class)->prefix('secure')->group(function(): void {
    Route::post('import', 'import');
    Route::get('export', 'export');
});




Route::middleware('auth:sanctum')->group(function (): void {
    Route::prefix('settings')->name('api.settings.')->middleware('administrator')->group(function (): void {
        Route::controller(MatrixController::class)
            ->prefix('matrix')
            ->name('matrix.')
            ->group(function () {
                Route::get('index', 'index')->name('index');
                Route::put('update', 'update')->name('update');
            });

        Route::controller(SettingsController::class)->group(function (): void {
            Route::get('index/{group}', 'index')->name('index');
            Route::put('update/{group}', 'update')->name('update');
            Route::put('bulk-update/{group}', 'bulkUpdate')->name('bulk_update');
        });


        Route::controller(EnvController::class)->group(function (): void {
            Route::get('mail-send-credentials', 'getMailSendCredentials')
                ->name('mail_send_credentials');
            Route::put('mail-send-credentials', 'updateMailSendCredentials')
                ->name('mail_send_credentials');
            Route::post('filters', 'setFilters')
                ->name('filters');
            Route::get('filters', 'getFilters')
                ->name('filters');
        });
    });

    Route::controller(UserSettingsController::class)
        ->prefix('benutzereinstellungen')
        ->name('user_settings.')
        ->group(function (): void {
            Route::post('filters', 'setFilters')->name('set_filters');
            Route::get('filters', 'getAllFilters')->name('get_all_filters');
            Route::get('filters/{group}', 'getFilters')->name('get_filters');
        });

	Route::controller(Fehlstunden::class)
		->name('api.fehlstunden.')
		->prefix('fehlstunden.')
		->group(function(): void {
			Route::post('fs/{leistung}', 'fs')->name('fs');
			Route::post('fsu/{leistung}', 'fsu')->name('fsu');
			Route::post('gfs/{schueler}', 'gfs')->name('gfs');
			Route::post('gfsu/{schueler}', 'gfsu')->name('gfsu');
		});

    Route::post('fachbezogene-bemerkung/{leistung}', FachbezogeneBemerkung::class)
		->name('api.fachbezogene_bemerkung');

    Route::post('mahnung/{leistung}', Mahnungen::class)
		->name('api.mahnung');

	Route::post('noten/{leistung}', Noten::class)
		->name('api.noten');

	Route::post('schueler-bemerkung/{schueler}', SchuelerBemerkung::class)
		->name('api.schueler_bemerkung');

    Route::get('floskeln/{floskelgruppe}', Floskeln::class)
		->name('api.floskeln');

    Route::get('fachbezogene-floskeln/{fach}', FachbezogeneFloskeln::class)
		->name('api.fachbezogene_floskeln');

	Route::get('leistungsdatenuebersicht', Leistungsdatenuebersicht::class)
		->name('api.leistungsdatenuebersicht');

	Route::get('mein-unterricht', MeinUnterricht::class)
		->name('api.mein_unterricht');

    Route::get('klassenleitung', Klassenleitung::class)
		->name('api.klassenleitung');
});


// TODO: To be removed, temporary testing route
// TODO: Testing
Route::get('export', ExportController::class)->name('api.export'); // Rename namespace?
Route::get('import/gzip', [ImportController::class, 'gzipEnm']);
Route::post('import/gzip', [ImportController::class, 'gzip']);
Route::post('import', [ImportController::class, 'request']);
Route::get('import', [ImportController::class, 'curl']);
Route::get('truncate', [DataImportService::class, 'truncate']);

Route::get('import/aes', AesController::class);


