<?php

use App\Http\Controllers\Api\FachbezogeneBemerkung;
use App\Http\Controllers\Api\FachbezogeneFloskeln;
use App\Http\Controllers\Api\Fehlstunden;
use App\Http\Controllers\Api\Floskeln;
use App\Http\Controllers\Api\Leistungsdatenuebersicht;
use App\Http\Controllers\Api\Klassenleitung;
use App\Http\Controllers\Api\MeinUnterricht;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\Noten;
use App\Http\Controllers\Api\Mahnungen;
use App\Http\Controllers\Api\SchuelerBemerkung;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ImportController;
use App\Services\DataImportService;
use Illuminate\Support\Facades\Route;

//Route::middleware('auth:sanctum')->get('/user', fn (Request $request) => $request->user());

Route::middleware('auth:sanctum')->group(function () {
	Route::controller(SettingController::class)
		->prefix(prefix: 'settings')
		->name('api.settings.')
		->group(function () {
			Route::get(uri: 'index/{type}', action: 'index')->name(name: 'index');
			Route::put(uri: 'update', action: 'update')->name(name: 'update');
		});

	Route::controller(Fehlstunden::class)->name('api.fehlstunden.')->group(function(): void {
		Route::post(uri: 'leistung/gesamt/{leistung}', action: 'fehlstundenLeistungGesamt')
			->name(name: 'leistung.gesamt');

		Route::post(uri: 'leistung/unentschuldigt/{leistung}', action: 'fehlstundenLeistungUnentschuldigt')
			->name(name: 'leistung.unentschuldigt');

		Route::post(uri: 'schueler/gesamt/{schueler}', action: 'fehlstundenSchuelerGesamt')
			->name(name: 'schueler.gesamt');

		Route::post(uri: 'schueler/gesamt-unentschuldigt/{schueler}', action: 'fehlstundenSchuelerGesamtUnentschuldigt')
			->name(name: 'schueler.gesamt_unentschuldigt');
	});

    Route::post(uri: 'fachbezogene-bemerkung/{leistung}', action: FachbezogeneBemerkung::class)
		->name(name: 'api.fachbezogene_bemerkung');

    Route::post(uri: 'mahnung/{leistung}', action: Mahnungen::class)
		->name(name: 'api.mahnung');

	Route::post(uri: 'noten/{leistung}', action: Noten::class)
		->name(name: 'api.noten');

	Route::post(uri: 'schueler-bemerkung/{schueler}', action: SchuelerBemerkung::class)
		->name(name: 'api.schueler_bemerkung');

    Route::get(uri: 'floskeln/{floskelgruppe}', action: Floskeln::class)
		->name(name: 'api.floskeln');

    Route::get(uri: 'fachbezogene-floskeln/{fach}', action: FachbezogeneFloskeln::class)
		->name(name: 'api.fachbezogene_floskeln');

	Route::get(uri: 'leistungsdatenuebersicht', action: Leistungsdatenuebersicht::class)
		->name(name: 'api.leistungsdatenuebersicht');

	Route::get(uri: 'mein-unterricht', action: MeinUnterricht::class)
		->name(name:'api.mein_unterricht');

    Route::get(uri: 'klassenleitung', action: Klassenleitung::class)
		->name(name: 'api.klassenleitung');
});


// TODO: To be removed, temporary testing route
// TODO: Testing
Route::get(uri: 'export', action: ExportController::class)->name('api.export'); // Rename namespace?
Route::post(uri: 'import', action: [ImportController::class, 'request']);
Route::get(uri: 'import', action: [ImportController::class, 'curl']);
Route::get(uri: 'truncate', action: [DataImportService::class, 'truncate']);
