<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Leistung;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class FachbezogeneBemerkung extends Controller
{
	public function __invoke(Leistung $leistung): JsonResponse
	{
		$leistung->update(
			attributes: ['fachbezogeneBemerkungen' => request()->bemerkung]
		);

		return response()->json(
			data: request()->all(),
			status: Response::HTTP_OK,
		);
	}
}