<?php

use App\Http\Controllers\Auth\RequestPasswordController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
	Route::inertia(uri: '/', component: 'MeinUnterricht')
		->name(name: 'mein_unterricht')
		->middleware(middleware: 'mein.unterricht');

	Route::inertia(uri: 'leistungsdatenuebersicht', component: 'Leistungsdatenuebersicht')
		->name(name: 'leistungsdatenuebersicht');

	Route::inertia(uri: 'klassenleitung', component: 'Klassenleitung')
		->name(name: 'klassenleitung')
		->middleware(middleware: 'klassenleitung');

	Route::middleware('administrator')
		->prefix('einstellungen')
		->name('settings.')
		->group(function () {
			Route::inertia('/', 'Settings/Index')->name('index');
			Route::inertia('schule', 'Settings/School')->name('school');
			Route::inertia('filter', 'Settings/Filter')->name('filter');
		});
});

Route::inertia(uri: 'impressum', component: 'Impressum')
	->name(name: 'impressum');

Route::inertia(uri: 'datenschutz', component: 'Datenschutz')
	->name(name: 'datenschutz');

Route::inertia(uri: 'barrierefreiheit', component: 'Barrierefreiheit')
	->name(name: 'barrierefreiheit');

Route::controller(RequestPasswordController::class)
	->middleware('guest')
	->name('request_password')
	->group(function () {
		Route::inertia(uri: 'passwort-anfordern', component: 'Auth/RequestPassword');
		Route::post(uri:'passwort-anfordern', action: 'store');
	});



