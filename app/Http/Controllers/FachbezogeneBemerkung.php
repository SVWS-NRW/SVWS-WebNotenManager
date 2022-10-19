<?php

namespace App\Http\Controllers;

use App\Models\LeistungNormalized;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


class FachbezogeneBemerkung extends Controller
{
	public function __invoke(LeistungNormalized $leistungNormalized): JsonResponse
	{
		$leistungNormalized->update(request()->all());
		$leistungNormalized->leistung->update(request()->all());

		return response()->json(request()->all(), Response::HTTP_OK);
	}
}
