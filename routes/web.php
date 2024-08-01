<?php

use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\TestMailController;
use Illuminate\Support\Facades\Route;

/*
 * Defines route group for authenticated and verified administrators
 */
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified', 'administrator'])
    ->prefix('einstellungen')
    ->name('settings.')
    ->group(function (): void {
        // unused at the moment because we want matrix as default and marked in blue
        // Route::inertia('/', 'Settings/Matrix')->name('index');
        Route::inertia('schule', 'Settings/School')->name('school');
        Route::inertia('filter', 'Settings/Filter')->name('filter');
        Route::inertia('matrix', 'Settings/Matrix')->name('matrix');
        Route::inertia('sicherheit', 'Settings/Sicherheit')->name('sicherheit');
        Route::inertia('synchronisation', 'Settings/Synchronisation')->name('synchronisation');
    });

/*
 * Defines route group for authenticated and verified users
 */
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {

    // Defines the Two-Factor Authentication route
	//testing 2FA here
	// Route::inertia(uri: 'two-factor.login', component: 'TwoFactorAuthentication')
	// 	->name(name: 'two-factor.login')
	// 	->middleware(middleware: 'two.fa');

    // Defines the Mein Unterricht route
	Route::inertia('/', 'MeinUnterricht')
		->name('mein_unterricht')
		->middleware('mein.unterricht');

    // Defines the Teilleistungen route
        Route::inertia('teilleistungen', 'Teilleistungen')
        ->name('teilleistungen');

    // Defines the Leistungsdatenuebersicht route
    Route::inertia('leistungsdatenuebersicht', 'Leistungsdatenuebersicht')
        ->name('leistungsdatenuebersicht');

    // Defines the Klassenleitung route
    Route::inertia('klassenleitung', 'Klassenleitung')
        ->name('klassenleitung')
        ->middleware('klassenleitung');

    // Defines the user settings route
    Route::inertia('/filter', 'UserSettings/UserSettingsFilter')
        ->prefix('benutzereinstellungen')
        ->name('user_settings.filter');

});

/*
 * Defines password request routes group for unauthenticated users
 */
Route::controller(PasswordController::class)
	->middleware('guest')
	->name('request_password.')
	->group(function (): void {
		Route::get('passwort-anfordern', 'index')->name('index');
		Route::post('passwort-anfordern', 'execute')->name('execute');
		Route::get('passwort-aendern/{token}', 'reset_form')->name('reset_form');
		Route::post('passwort-aendern', 'update')->name('update');
	});

/*
 * Defines routes for unauthenticated users
 */
Route::inertia('impressum', 'Impressum')->name('impressum');
Route::inertia('datenschutz', 'Datenschutz')->name('datenschutz');
Route::inertia('barrierefreiheit', 'Barrierefreiheit')->name('barrierefreiheit');
