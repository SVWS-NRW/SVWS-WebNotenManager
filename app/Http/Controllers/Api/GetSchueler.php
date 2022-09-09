<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Schueler;
use Illuminate\Http\JsonResponse;

class GetSchueler extends Controller
{
	public function __invoke(): JsonResponse
	{
		return response()->json(Schueler::with('bemerkung')->get());
	}
}
