<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LeistungResource;
use App\Models\Lehrer;
use App\Models\Leistung;
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
	public function __invoke(): AnonymousResourceCollection
	{
		$klassen = auth()->user()->klassen()->select(columns: 'id');

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
			->with(relations: $eagerLoadedColumns)
			->when(
				value: auth()->user() instanceof Lehrer,
				callback: fn (Builder $query): Builder => $query->whereHas(
					relation: 'schueler',
					callback: fn (Builder $query): Builder => $query->whereIn(column: 'klasse_id', values: $klassen)
				)
			)
			->get()
			->sortBy(callback: $sortByColumns);

        return LeistungResource::collection(resource: $leistungen);
    }
}
