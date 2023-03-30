<?php

namespace App\Http\Controllers;

use App\Http\Resources\Matrix\JahrgangResource;
use App\Models\Jahrgang;
use App\Models\Klasse;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

class KlassenMatrix extends Controller
{
    public function index(): Collection
	{
		$resource = Jahrgang::query()
			->with(
				relations: 'klassen',
				callback: fn (HasMany $related): HasMany => $related->orderBy(column: 'sortierung')
			)
			->orderBy(column: 'sortierung')
			->get();

		return JahrgangResource::collection(resource: $resource)
			->collection
			->groupBy(groupBy: 'stufe');
	}

	public function update(Klasse $klasse): JsonResponse
	{
		$klasse->update(attributes: [
			request()->key => request()->value
		]);

		return response()->json(status: Response::HTTP_NO_CONTENT);
	}
}
