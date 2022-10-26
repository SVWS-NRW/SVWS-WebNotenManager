<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LeistungNormalized;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;

class GetLeistungen extends Controller
{
    public function __invoke(): JsonResponse
    {
		$leistungen = LeistungNormalized::query()
			->when(auth()->user()->lehrer(), fn (Builder $query) =>
				$query->whereIn('klasse', auth()->user()->klassen->pluck('kuerzel'))
			)
			->orderBy('klasse')
			->orderBy('nachname')
			->orderBy('fach')
			->get();

        return response()->json($leistungen);
    }
}
