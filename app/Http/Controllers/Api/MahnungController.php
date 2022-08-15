<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LeistungNormalized;
use Symfony\Component\HttpFoundation\Response;

class MahnungController extends Controller
{
    public function __invoke(LeistungNormalized $leistungNormalized)
    {
        $istGemahnt = request()->istGemahnt;

        $leistungNormalized->update(['istGemahnt' => $istGemahnt]);
        $leistungNormalized->leistung->update([
            'istGemahnt' => $istGemahnt,
            'mahndatum' => $istGemahnt ? now() : null,
        ]);

        return response()->json(['istGemahnt' => $istGemahnt], Response::HTTP_OK);
    }
}