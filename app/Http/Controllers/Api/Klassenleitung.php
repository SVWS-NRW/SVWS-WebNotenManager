<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Klassenleitung\SchuelerResource;
use App\Models\Schueler;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class Klassenleitung extends Controller
{
	public function __invoke(): AnonymousResourceCollection
	{
		$schueler = Schueler::query()
			->with(['klasse', 'leistungen', 'bemerkung', 'lernabschnitt'])
			->when(
				auth()->user()->isLehrer(),
				fn (Builder $query): Builder => $query->whereIn(
					'klasse_id',
					auth()->user()->klassen()->pluck(column: 'id')
				)
			)
			->get()
			->sortBy(fn (Schueler $schueler): array => [
				$schueler->klasse->kuerzel,
				$schueler->nachname,
			]);

		return SchuelerResource::collection($schueler);
	}
}
