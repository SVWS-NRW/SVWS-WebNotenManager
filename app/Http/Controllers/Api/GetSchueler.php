<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\KlassenleitungResource;
use App\Models\Lehrer;
use App\Models\Schueler;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class GetSchueler extends Controller
{
	public function __invoke(): AnonymousResourceCollection
	{
		abort_unless(auth()->user() instanceof Lehrer, Response::HTTP_FORBIDDEN);

		$sorter = fn (Schueler $schueler): array => [
			$schueler->klasse->kuerzel,
			$schueler->nachname,
		];

		$schueler = Schueler::query()
			->with(['klasse', 'leistungen', 'bemerkung'])
			->whereIn('klasse_id', auth()->user()->klassen()->pluck('id'))
			->get()
			->sortBy($sorter);

		return KlassenleitungResource::collection($schueler);
	}
}
