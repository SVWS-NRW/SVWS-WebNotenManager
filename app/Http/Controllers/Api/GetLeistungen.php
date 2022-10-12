<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LeistungNormalized;
use DB;
use Illuminate\Http\JsonResponse;

class GetLeistungen extends Controller
{
    public function __invoke(): JsonResponse
    {
		$leistungen = LeistungNormalized::query()
			->whereIn('lerngruppe_id', auth()->user()->lerngruppen->pluck('id'))
			->orderBy(DB::raw('ISNULL(klasse), klasse'), 'ASC')
			->orderBy('nachname')
			->get();

        return response()->json($leistungen);
    }
}
