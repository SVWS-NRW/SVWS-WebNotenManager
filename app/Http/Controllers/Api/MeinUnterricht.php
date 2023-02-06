<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LeistungResource;
use App\Models\Lehrer;
use App\Models\Leistung;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MeinUnterricht extends Controller
{
	public function __invoke(): AnonymousResourceCollection
	{
		$lerngruppen = auth()->user()->lerngruppen->pluck('id')->toArray();

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
					relation: 'lerngruppe',
					callback: fn (Builder $query): Builder => $query->whereIn(column: 'id', values: $lerngruppen)
				)
			)
			->get()
			->sortBy(callback: $sortByColumns);

		return LeistungResource::collection(resource: $leistungen);
	}
}
