<?php

use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Api\Settings\UserSettingsController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {

	//testing 2FA here
	// Route::inertia(uri: 'two-factor.login', component: 'TwoFactorAuthentication')
	// 	->name(name: 'two-factor.login')
	// 	->middleware(middleware: 'two.fa');
	Route::inertia(uri: '/', component: 'MeinUnterricht')
		->name(name: 'mein_unterricht')
		->middleware(middleware: 'mein.unterricht');

	Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function (): void {
		Route::inertia('/', 'MeinUnterricht')
			->name('mein_unterricht')
			->middleware('mein.unterricht');

		Route::inertia('leistungsdatenuebersicht', 'Leistungsdatenuebersicht')
			->name('leistungsdatenuebersicht');

		Route::inertia('klassenleitung', 'Klassenleitung')
			->name('klassenleitung')
			->middleware('klassenleitung');

		Route::middleware('administrator')
			->prefix('einstellungen')
			->name('settings.')
			->group(function (): void {
				Route::inertia('/', 'Settings/Matrix')->name('index');
				Route::inertia('schule', 'Settings/School')->name('school');
				Route::inertia('filter', 'Settings/Filter')->name('filter');
				Route::inertia('matrix', 'Settings/Matrix')->name('matrix');
				Route::inertia('sicherheit', 'Settings/Sicherheit')->name('sicherheit');

				Route::inertia('synchronisation', 'Settings/Synchronisation')->name('synchronisation');
			});

		Route::inertia('/filter', 'UserSettings/UserSettingsFilter')
			->prefix('benutzereinstellungen')
			->name('user_settings.filter');
	});
});

Route::controller(PasswordController::class)
	->middleware('guest')
	->name('request_password.')
	->group(function (): void {
		Route::get('passwort-anfordern', 'index')->name('index');
		Route::post('passwort-anfordern', 'execute')->name('execute');
		Route::get('passwort-aendern/{token}', 'reset_form')->name('reset_form');
		Route::post('passwort-aendern', 'update')->name('update');
	});

Route::inertia('impressum', 'Impressum')->name('impressum');
Route::inertia('datenschutz', 'Datenschutz')->name('datenschutz');
Route::inertia('barrierefreiheit', 'Barrierefreiheit')->name('barrierefreiheit');