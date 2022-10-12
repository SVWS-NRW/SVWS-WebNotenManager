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
	Route::inertia('einstellungen', 'Settings/Index')->name('settings.index');
	Route::inertia('einstellungen/schule', 'Settings/School')->name('settings.school');
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