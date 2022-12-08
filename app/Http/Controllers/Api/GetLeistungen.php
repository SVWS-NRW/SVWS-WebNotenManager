<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LeistungResource;
use App\Models\Lehrer;
use App\Models\Leistung;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GetLeistungen extends Controller
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
		$klassen = auth()->user()->klassen()->select('id');

		$leistungen = Leistung::query()
			->with([
				'schueler' => ['klasse', 'jahrgang'],
				'lerngruppe' => ['lehrer', 'fach'],
				'note',
			])
			->when(auth()->user() instanceof Lehrer, fn (Builder $query)
				=> $query->whereHas('schueler', fn (Builder $query): Builder
					=> $query->whereIn('klasse_id', $klassen)
				)
			)
			->get()
			->sortBy([
				'schueler.klasse.kuerzel',
				'schueler.nachname',
				'lerngruppe.fach.kuerzelAnzeige',
			]);

        return LeistungResource::collection($leistungen);
    }
}
