<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Leistung;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class Mahnungen extends Controller
{
    public function __invoke(Leistung $leistung): JsonResponse
	{
		abort_unless(
			boolean: $leistung->schueler->klasse->editable_mahnungen,
			code: Response::HTTP_FORBIDDEN
		);

		$leistung->update(attributes: [
			'istGemahnt' => request()->istGemahnt,
			'tsIstGemahnt' => now()->format(format: 'Y-m-d H:i:s.u'),
		]);

        return response()->json(
			status: Response::HTTP_NO_CONTENT
		);
    }
}