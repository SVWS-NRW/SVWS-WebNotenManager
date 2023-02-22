<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SchuelerBemerkungenRequest;
use App\Models\Bemerkung;
use App\Models\Schueler;
use Symfony\Component\HttpFoundation\Response;

class SchuelerBemerkung extends Controller
{
    public function __invoke(SchuelerBemerkungenRequest $request, Schueler $schueler): Response
    {
		Bemerkung::updateOrCreate(
			attributes: ['schueler_id' => $schueler->id],
			values: [
				$request->key => $request->value,
				"ts{$request->key}" => now()->format(format: 'Y-m-d H:i:s.u'),
			]
		);

		return response()->json(status: Response::HTTP_NO_CONTENT);
    }
}
