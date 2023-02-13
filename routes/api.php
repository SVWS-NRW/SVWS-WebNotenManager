<?php

use App\Http\Controllers\Api\FachbezogeneBemerkung;
use App\Http\Controllers\Api\FachbezogeneFloskeln;
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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', fn (Request $request) => $request->user());

Route::middleware('auth:sanctum')->group(function () {

	Route::middleware('administrator')
		->controller(SettingController::class)
		->group(function () {
			Route::get('get-settings/{type}', 'get')->name('get_settings');
			Route::post('set-settings', 'set')->name('set_settings');
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


// TODO: To ber removed, temporary testing route
Route::get(uri: 'export', action: ExportController::class);
Route::post(uri: 'import', action: [ImportController::class, 'request']);
Route::get(uri: 'import', action: [ImportController::class, 'curl']);
Route::get(uri: 'truncate', action: [DataImportService::class, 'truncate']);
