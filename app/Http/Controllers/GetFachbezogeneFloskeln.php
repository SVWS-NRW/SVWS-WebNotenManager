<?php

namespace App\Http\Controllers;

use App\Http\Resources\FachBezogeneFloskelResource;
use App\Models\Floskel;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GetFachbezogeneFloskeln extends Controller
{
    public function __invoke(Request $request): AnonymousResourceCollection
	{
		return FachBezogeneFloskelResource::collection(
			Floskel::whereNotNull('fach_id')->orderBy('kuerzel')->get()
		);
    }
}
