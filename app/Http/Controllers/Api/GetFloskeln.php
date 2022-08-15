<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\FloskelgruppeCollection;
use App\Http\Controllers\Controller;
use App\Models\Floskelgruppe;

class GetFloskeln extends Controller
{
    public function __invoke(): FloskelgruppeCollection
    {
        $floskeln = Floskelgruppe::query()
            ->whereHas('floskeln')
            ->with('floskeln', 'floskeln.floskelgruppe')
            ->get();

        return new FloskelgruppeCollection($floskeln);
    }
}
