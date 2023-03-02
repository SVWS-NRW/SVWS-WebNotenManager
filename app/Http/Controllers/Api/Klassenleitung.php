<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\KlassenleitungResource;
use App\Models\Schueler;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class Klassenleitung extends Controller
{
	public function __invoke(): AnonymousResourceCollection
	{
		$schueler = Schueler::query()
			->with(relations: ['klasse', 'leistungen', 'bemerkung'])
			->when(auth()->user()->isLehrer(), fn ($q) => $q			->whereIn(
				column: 'klasse_id',
				values: auth()->user()->klassen()->pluck(column: 'id')
			))
			->get()
			->sortBy(callback: fn (Schueler $schueler): array => [
				$schueler->klasse->kuerzel,
				$schueler->nachname,
			]);

		return KlassenleitungResource::collection(resource: $schueler);
	}
}
