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
		// TODO: Add tests
		abort_unless(
			boolean: $leistung->schueler->klasse->editable_fb,
			code: Response::HTTP_FORBIDDEN
		);

		$leistung->update(
			attributes: [
				'fachbezogeneBemerkungen' => request()->bemerkung,
				'tsFachbezogeneBemerkungen' => now()->format(format: 'Y-m-d H:i:s.u'),
			]
		);

		return response()->json(status: Response::HTTP_NO_CONTENT);
	}
}
