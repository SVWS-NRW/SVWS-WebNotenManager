<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Matrix\JahrgangResource;
use App\Http\Resources\Matrix\KlasseResource;
use App\Models\Jahrgang;
use App\Models\Klasse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

class KlassenMatrix extends Controller
{
    public function index(): JsonResponse
	{
		$jahrgaenge =  JahrgangResource::collection(resource: Jahrgang::orderedWithKlassenOrdered())
			->collection
			->groupBy(groupBy: 'stufe');

		$klassen = KlasseResource::collection(resource: Klasse::notBelongingToJahrgangOrdered());

		return response()->json(data: [
			'jahrgaenge' => $jahrgaenge,
			'klassen' => $klassen,
		]);
	}

	public function update(Klasse $klasse): JsonResponse
	{
		$klasse->update(attributes: [
			request()->key => request()->value
		]);

		return response()->json(status: Response::HTTP_NO_CONTENT);
	}
}
