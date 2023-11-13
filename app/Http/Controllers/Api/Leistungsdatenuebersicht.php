<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MeinUnterricht\LeistungResource;
use App\Models\Leistung;
use App\Settings\FilterSettings;
use App\Settings\MatrixSettings;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class Leistungsdatenuebersicht extends Controller
{
	/**
	 * Fetches all Leistung Models records and returns as a formatted resource.
	 * Lehrer users get only records where the Klasse Model intersects with their Klasse relationship.
	 * Admin users get all records returned.
	 *
	 * @return AnonymousResourceCollection
	 */
	public function __invoke(FilterSettings $settings, MatrixSettings $matrix): AnonymousResourceCollection
	{
		$eagerLoadedColumns = [
			'schueler' => ['klasse', 'jahrgang'],
			'lerngruppe' => ['lehrer', 'fach'],
			'note',
		];

		$sortByColumns = [
			'schueler.klasse.kuerzel',
			'schueler.nachname',
			'lerngruppe.fach.kuerzelAnzeige',
		];

		$leistungen = Leistung::query()
			->with($eagerLoadedColumns)
			->when(
				auth()->user()->isLehrer(),
				fn (Builder $query): Builder => $query->whereHas(
					'schueler',
					fn (Builder $query): Builder => $query->whereIn(
						'klasse_id',
						auth()->user()->klassen()->select('id')
					)
				)
			)
			->get()
			->sortBy($sortByColumns);

        return LeistungResource::collection($leistungen)      // TODO: @karol refactor after usersettings are present
            ->additional([
                'toggles' => [
                    'teilleistungen' => $settings->leistungdatenuebersicht_teilleistungen,
                    'mahnungen' => $settings->leistungdatenuebersicht_mahnungen,
                    'bemerkungen' => $settings->leistungdatenuebersicht_bemerkungen,
                    'fachlehrer' => $settings->leistungdatenuebersicht_fachlehrer,
                ],
                'lehrerCanOverrideFachlehrer' => $matrix->lehrer_can_override_fachlehrer,
            ]);
    }
}
