<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FloskelResource;
use App\Models\Floskel;
use App\Models\Floskelgruppe;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Floskeln extends Controller
{
    public function __invoke(string $floskelgruppe): Collection|JsonResponse|array
	{
		try {
			$gruppe = Floskelgruppe::query()
				->where(
					column: 'kuerzel',
					operator: '=',
					value: $floskelgruppe
				)
				->firstOrFail();

		} catch (NotFoundHttpException $e) {
			return response()->json(
				data: $e->getMessage(),
				status: Response::HTTP_NOT_FOUND,
			);
		}

		return response()->json(data: FloskelResource::collection(
			resource: Floskel::query()->whereBelongsTo(related: $gruppe)->get()
		));
	}
}
