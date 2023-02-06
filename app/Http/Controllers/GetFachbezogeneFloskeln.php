<?php

namespace App\Http\Controllers;

use App\Http\Resources\FachBezogeneFloskelResource;
use App\Models\Fach;
use App\Models\Floskel;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GetFachbezogeneFloskeln extends Controller
{
    public function __invoke(Fach $fach): AnonymousResourceCollection
	{
		return FachBezogeneFloskelResource::collection(
			resource: Floskel::query()
				->whereBelongsTo(related: $fach)
				->with(relations: 'jahrgang')
				->get()
		);
    }
}
