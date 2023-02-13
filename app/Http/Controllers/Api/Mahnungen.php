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
		$leistung->update(attributes: [
			'istGemahnt' => request()->istGemahnt,
			'tsIstGemahnt' => now()->format(format: 'Y-m-d H:i:s.u'),
		]);

        return response()->json(
			data: request()->all(),
			status: Response::HTTP_OK
		);
    }
}