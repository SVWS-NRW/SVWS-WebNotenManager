<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Leistung;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class Mahnungen extends Controller
{
    public function __invoke(Leistung $leistung): JsonResponse
	{
		abort_unless($leistung->schueler->klasse->editable_mahnungen, Response::HTTP_FORBIDDEN);

		$leistung->update([
			'istGemahnt' => request()->istGemahnt,
			'tsIstGemahnt' => now()->format('Y-m-d H:i:s.u'),
		]);

        return response()->json(Response::HTTP_NO_CONTENT);
    }
}