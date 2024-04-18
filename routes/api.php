<?php

use App\Http\Controllers\Api\{
    FachbezogeneBemerkung, FachbezogeneFloskeln, Fehlstunden, Floskeln, Klassenleitung, Leistungsdatenuebersicht,
    Mahnungen, MeinUnterricht, Noten, SchuelerBemerkung, TwoFAAuthentication
};
use App\Http\Controllers\Api\Settings\{
    EnvController, MatrixController, SettingsController, UserSettingsController
};
use App\Http\Controllers\{PassportController, SecureTransferController};
use Illuminate\Support\Facades\Route;

/*
 * Define a controller route for SecureTransferController with middleware and prefix
 */
Route::controller(SecureTransferController::class)
	->middleware('client')
    ->prefix('secure')
    ->name('secure.')
	->group(function (): void {
		Route::get('check', 'check')->name('check');
		Route::get('export', 'export')->name('export');
		Route::post('import', 'import')->name('import');
		Route::post('truncate', 'truncate')->name('truncate');
	});

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified', 'administrator'])
    ->group(function (): void {
        // Defines the Matrix Settings route group for administrators
        // TODO: To be checked by Karol
        Route::controller(MatrixController::class)
            ->prefix('matrix')
            ->name('api.matrix.')
            ->group(function () {
                Route::get('index', 'index')->name('index');
                Route::put('update', 'update')->name('update');
            });

        // Defines the Passport Oauth2 route group for administrators
        Route::resource('settings/passport', PassportController::class)
            ->only('index', 'store');

        // TODO: To be checked by Karol
        Route::controller(SettingsController::class)
            ->prefix('settings')
            ->name('api.settings.')
            ->group(function (): void {
                // Defines the matrix settings controller route group for administrators
                Route::controller(MatrixController::class)
                    ->prefix('matrix')
                    ->name('matrix.')
                    ->group(function () {
                        Route::get('index', 'index')->name('index');
                        Route::put('update', 'update')->name('update');
                    });

                // Defines the general settings controller route group for administrators
                Route::controller(SettingsController::class)->group(function (): void {
                    Route::get('index/{group}', 'index')->name('index');
                    Route::put('update/{group}', 'update')->name('update');
                    Route::put('bulk-update/{group}', 'bulkUpdate')->name('bulk_update');
                });

                // Defines the .nnv settings controller route group for administrators
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

    });

Route::middleware('auth:sanctum')->group(function () {

    // Defines the Two-Factor Authentication route group
	Route::controller(TwoFAAuthentication::class)
        //testing 2fa here
        //should probably be up there (let's think about it)
		->group(function(): void {
			Route::post('activate2FA', 'activate2FA')->name('activate2FA');;
			Route::delete('deactivate2FA', 'deactivate2FA')->name('deactivate2FA');;
	    });


    // Defines the Fehlstunden controller route group
	Route::controller(Fehlstunden::class)
		->name('api.fehlstunden.')
		->prefix('fehlstunden.')
		->group(function (): void {
			Route::post('fs/{leistung}', 'fs')->name('fs');
			Route::post('fsu/{leistung}', 'fsu')->name('fsu');
			Route::post('gfs/{schueler}', 'gfs')->name('gfs');
			Route::post('gfsu/{schueler}', 'gfsu')->name('gfsu');
		});

    // Defines the user settings controller route group
    Route::controller(UserSettingsController::class)
        ->prefix('benutzereinstellungen')
        ->name('user_settings.')
        ->group(function (): void {
            Route::post('filters', 'setFilters')->name('set_filters');
            Route::get('filters', 'getAllFilters')->name('get_all_filters');
            Route::get('filters/{group}', 'getFilters')->name('get_filters');
            Route::get('user-data', 'getLastLogin')->name('get_last_login');
        });

    // Defines the Fachbezogene Bemerkung controller route group
	Route::post('fachbezogene-bemerkung/{leistung}', FachbezogeneBemerkung::class)
		->name('api.fachbezogene_bemerkung');

    // Defines the Mahnung controller route group
	Route::post('mahnung/{leistung}', Mahnungen::class)
		->name('api.mahnung');

    // Defines the Noten controller route group
	Route::post('noten/{leistung}', Noten::class)
		->name('api.noten');

    // Defines the Schueler Bemerkung controller route group
	Route::post('schueler-bemerkung/{schueler}', SchuelerBemerkung::class)
		->name('api.schueler_bemerkung');

    // Defines the Floskeln controller route group
	Route::get('floskeln/{floskelgruppe}', Floskeln::class)
		->name('api.floskeln');

    // Defines the Fachbezogene Floskeln controller route group
	Route::get('fachbezogene-floskeln/{fach}', FachbezogeneFloskeln::class)
		->name('api.fachbezogene_floskeln');

    // Defines the Leistungsdatenuebersicht controller route group
	Route::get('leistungsdatenuebersicht', Leistungsdatenuebersicht::class)
		->name('api.leistungsdatenuebersicht');

    // Defines the Mein Unterricht controller route group
	Route::get('mein-unterricht', MeinUnterricht::class)
		->name('api.mein_unterricht');

    // Defines the Klassenleitung controller route group
	Route::get('klassenleitung', Klassenleitung::class)
		->name('api.klassenleitung');
});
