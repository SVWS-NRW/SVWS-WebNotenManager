<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\KlassenleitungResource;
use App\Models\Lehrer;
use App\Models\Schueler;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class Klassenleitung extends Controller
{
	public function __invoke(): AnonymousResourceCollection
	{
		abort_unless(
			boolean: auth()->user() instanceof Lehrer,
			code: Response::HTTP_FORBIDDEN
		);

		$klassen = auth()->user()->klassen()->pluck(column: 'id');

		$schueler = Schueler::query()
			->with(relations: ['klasse', 'leistungen', 'bemerkung'])
			->whereIn(column: 'klasse_id', values: $klassen)
			->get()
			->sortBy(callback: fn (Schueler $schueler): array => [
				$schueler->klasse->kuerzel,
				$schueler->nachname,
			]);

		return KlassenleitungResource::collection(resource: $schueler);
	}
}
