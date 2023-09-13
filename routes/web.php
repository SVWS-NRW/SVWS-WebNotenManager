<?php

use App\Http\Controllers\Auth\PasswordController;
use Illuminate\Support\Facades\Route;

Route::inertia('impressum', 'Impressum')->name('impressum');
Route::inertia('datenschutz', 'Datenschutz')->name('datenschutz');
Route::inertia('barrierefreiheit', 'Barrierefreiheit')->name('barrierefreiheit');

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


