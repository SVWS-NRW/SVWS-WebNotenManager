<?php

use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\TwoFactorAuthentification\OtpController;
use Illuminate\Support\Facades\Route;

/*
 * Defines route group for authenticated and verified administrators
 */
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified', 'administrator', 'twofactor.otp'])
    ->prefix('einstellungen')
    ->name('settings.')
    ->group(function (): void {
        // unused at the moment because we want matrix as default and marked in blue
        // Route::inertia('/', 'Settings/Matrix')->name('index');
        Route::inertia('schule', 'Settings/School')->name('school');
        Route::inertia('filter', 'Settings/Filter')->name('filter');
        Route::inertia('matrix', 'Settings/Matrix')->name('matrix');
        Route::inertia('sicherheit', 'Settings/Sicherheit')->name('sicherheit');
        Route::inertia('2fa', 'Settings/ZweifaktorAuthentisierung')->name('2fa');
        Route::inertia('synchronisation', 'Settings/Synchronisation')->name('synchronisation');
    });

/*
 * Defines route group for email two factor authentication (otp)
 */
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])
    ->prefix('two-factor/otp')
    ->group(function (): void {
        Route::inertia('/', 'TwoFactor/Otp')->name('otp')->middleware('otp.pending');
        Route::post('/verify', [OtpController::class, 'verify'])->name('otp.verify');
    });

/*
 * Defines route group for authenticated and verified users
 */
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified', 'twofactor.otp'])->group(function () {

    // Defines the Mein Unterricht route
	Route::inertia('/', 'MeinUnterricht')->name('mein_unterricht')->middleware('has.lerngruppen');

    // Defines the Teilleistungen route
    Route::inertia('teilleistungen', 'Teilleistungen')->name('teilleistungen')->middleware('has.lerngruppen');

    // Defines the Leistungsdatenuebersicht route
    Route::inertia('leistungsdatenuebersicht', 'Leistungsdatenuebersicht')->name('leistungsdatenuebersicht');

    // Defines the Klassenleitung route
    Route::inertia('klassenleitung', 'Klassenleitung')->name('klassenleitung')->middleware('klassenleitung');

    // Defines the user setings routes
    Route::prefix('benutzereinstellungen')->group(function (): void {
        Route::inertia('/filter', 'UserSettings/Filter')->name('user_settings.filter');
        Route::inertia('/security', 'UserSettings/Security')->name('user_settings.security');
    });
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
