<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\KlassenleitungResource;
use App\Models\Schueler;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class Klassenleitung extends Controller
{
	public function __invoke(): AnonymousResourceCollection
	{
		$schueler = Schueler::query()
			->with(relations: ['klasse', 'leistungen', 'bemerkung', 'lernabschnitt'])
			->when(
				value: auth()->user()->isLehrer(),
				callback: fn (Builder $query): Builder => $query->whereIn(
					column: 'klasse_id',
					values: auth()->user()->klassen()->pluck(column: 'id')
				)
			)
			->get()
			->sortBy(callback: fn (Schueler $schueler): array => [
				$schueler->klasse->kuerzel,
				$schueler->nachname,
			]);

		return KlassenleitungResource::collection(resource: $schueler);
	}
}
