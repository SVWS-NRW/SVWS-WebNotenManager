<?php

use App\Http\Controllers\Auth\RequestPasswordController;
use App\Http\Controllers\KlassenleitungController;
use App\Http\Controllers\LeistungsController;
use App\Http\Controllers\LeistungsUebersichtController;
use Illuminate\Support\Facades\Route;

Route::get('test', function () {
	dd(auth()->user());
});

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
	Route::get('/', LeistungsController::class)->name('dashboard');

	Route::get('leistungsdatenuebersicht', LeistungsUebersichtController::class)
		->name('leistungsdatenuebersicht');

	Route::get('klassenleitung', KlassenleitungController::class)
		->name('klassenleitung')
		->middleware('klassenleitung');

	Route::middleware('admin')
		->prefix('einstellungen')
		->namespace('settings.')
		->group(function () {
			Route::inertia('/', 'Settings/Index')->name('index');
			Route::inertia('schule', 'Settings/School')->name('school');
		});
});

Route::inertia('impressum', 'Impressum')->name('impressum');
Route::inertia('datenschutz', 'Datenschutz')->name('datenschutz');
Route::inertia('barrierefreiheit', 'Barrierefreiheit')->name('barrierefreiheit');

Route::controller(RequestPasswordController::class)
	->middleware('guest')
	->name('request_password')
	->group(function () {
		Route::get('passwort-anfordern', 'index');
		Route::post('passwort-anfordern', 'store');
	});



