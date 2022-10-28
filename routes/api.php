<?php

use App\Http\Controllers\Api\GetBemerkungen;
use App\Http\Controllers\Api\GetFloskeln;
use App\Http\Controllers\Api\GetLeistungen;
use App\Http\Controllers\Api\GetSchueler;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\NotenController;
use App\Http\Controllers\Api\MahnungController;
use App\Http\Controllers\Api\SchuelerBemerkung;
use App\Http\Controllers\FachbezogeneBemerkung;
use App\Http\Controllers\GetFachbezogeneFloskeln;
use App\Http\Controllers\GetFilters;
use App\Services\DataExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', fn (Request $request) => $request->user());

Route::middleware('auth:sanctum')->group(function () {
    Route::controller(GetBemerkungen::class)->group(function () {
        Route::get('get-bemerkung/{leistungNormalized}/{group}', 'get')->name('get_bemerkungen');
        Route::post('set-bemerkung/{leistungNormalized}', 'set')->name('set_bemerkungen');
    });

	Route::controller(NotenController::class)->group(function () {
        Route::get('get-noten', 'get')->name('get_noten');
        Route::post('set-noten/{leistungNormalized}', 'set')->name('set_noten');
    });

	Route::middleware('admin')
		->controller(SettingController::class)->group(function () {
			Route::get('get-settings/{type}', 'get')->name('get_settings');
			Route::post('set-settings', 'set')->name('set_settings');
		});


    Route::get('get-floskeln', GetFloskeln::class)->name('get_floskeln');
    Route::get('get-leistungen', GetLeistungen::class)->name('get_leistungen');
    Route::get('get-filters', [GetFilters::class, 'dashboard'])->name('get_filters');
    Route::get('get-fachbezogene-floskeln-filters', [GetFilters::class, 'fachbezogeneFloskeln'])->name('get_fachbezogene-floskeln_filters');
    Route::get('get-schueler', GetSchueler::class)->name('get_schueler');
    Route::post('set-schueler-bemerkung/{schueler}', SchuelerBemerkung::class)->name('set_schueler_bemerkung');

    Route::post('set-fachbezogene-bemerkung/{leistungNormalized}', FachbezogeneBemerkung::class)->name('set_fachbezogene_bemerkung');
    Route::get('get-fachbezogene-floskeln', GetFachbezogeneFloskeln::class)->name('get_fachbezogene_floskeln');


    Route::post('set-mahnung/{leistungNormalized}', MahnungController::class)->name('set_mahnung');
});


Route::get('export/{id}', [DataExportService::class, 'export']);
