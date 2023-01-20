<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\FloskelgruppeCollection;
use App\Http\Controllers\Controller;
use App\Models\Floskel;
use App\Models\Floskelgruppe;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetFloskeln extends Controller
{
    public function __invoke(string $floskelgruppe)
    {
		try {
			$gruppe = Floskelgruppe::where('kuerzel', '=', $floskelgruppe)->firstOrFail();
		} catch (NotFoundHttpException $e) {
			return response()->json($e->getMessage(), Response::HTTP_NOT_FOUND);
		}

        return Floskel::query()
            ->whereBelongsTo($gruppe)
            ->get();
    }
}
