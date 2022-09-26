<?php

use App\Http\Controllers\Auth\RequestPasswordController;
use App\Http\Controllers\Datenschutz;
use App\Http\Controllers\Impressum;
use App\Http\Controllers\KlassenleitungController;
use App\Http\Controllers\LeistungsController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
	Route::get('/', LeistungsController::class)->name('dashboard');
	Route::get('klassenleitung', KlassenleitungController::class)->name('klassenleitung');
	Route::get('einstellungen', SettingsController::class)->name('settings');
});

Route::get('impressum', Impressum::class)->name('impressum');
Route::get('datenschutz', Datenschutz::class)->name('datenschutz');

Route::controller(RequestPasswordController::class)
	->middleware('guest')
	->name('request_password')
	->group(function () {
		Route::get('passwort-anfordern', 'index');
		Route::post('passwort-anfordern', 'store');
	});
