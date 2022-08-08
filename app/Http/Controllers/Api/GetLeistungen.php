<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LeistungNormalized;
use Illuminate\Http\JsonResponse;

class GetLeistungen extends Controller
{
    public function __invoke(): JsonResponse
    {
        return response()->json(LeistungNormalized::all());
    }
}
