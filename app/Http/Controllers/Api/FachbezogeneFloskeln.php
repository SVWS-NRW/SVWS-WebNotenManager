<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FachBezogeneFloskelResource;
use App\Models\Fach;
use App\Models\Floskel;
use App\Models\Floskelgruppe;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FachbezogeneFloskeln extends Controller
{
	public function __invoke(Fach $fach): AnonymousResourceCollection
	{
		try {
			$floskelgruppe = Floskelgruppe::query()
				->where(column: 'kuerzel', operator: '=', value: 'FACH')
				->firstOrFail();
		} catch (ModelNotFoundException $e) {
			return FachBezogeneFloskelResource::collection(resource: []);
		}

		$floskeln = Floskel::query()
			->whereBelongsTo(related: $floskelgruppe)
			->whereBelongsTo(related: $fach)
			->with(relations: 'jahrgang')
			->get();

		$mapNiveau = fn (Floskel $floskel): array => [
			'index' => $floskel->niveau,
			'label' => $floskel->niveau
		];

		$mapJahrgaenge = fn (Floskel $floskel): array => [
			'index' => $floskel->jahrgang?->kuerzel,
			'label' => $floskel->jahrgang?->kuerzel,
		];

		return FachBezogeneFloskelResource::collection(
			resource: $floskeln
		)->additional(data: [
			'niveau' => $floskeln
				->unique(key: 'niveau')
				->map(callback: $mapNiveau)
				->values(),
			'jahrgaenge' => $floskeln
				->unique(key: 'jahrgang_id')
				->map(callback: $mapJahrgaenge)
				->values(),
		]);
	}
}