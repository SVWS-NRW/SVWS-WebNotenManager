<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\KlassenleitungResource;
use App\Models\Schueler;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GetSchueler extends Controller
{
	public function __invoke(): AnonymousResourceCollection
	{
		return KlassenleitungResource::collection(
			Schueler::query()
				->with('klasse')
				->whereIn('klasse_id', auth()->user()->klassen()->pluck('id'))
				->get()
		);
	}
}
