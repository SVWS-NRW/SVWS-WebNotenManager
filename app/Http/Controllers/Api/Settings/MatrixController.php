<?php

namespace App\Http\Controllers\Api\Settings;

use App\Http\Controllers\Controller;
use App\Http\Resources\Matrix\JahrgangResource;
use App\Http\Resources\Matrix\KlasseResource;
use App\Models\Jahrgang;
use App\Models\Klasse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response;

class MatrixController extends Controller
{
    public function index(): JsonResponse
    {
        $jahrgaenge = JahrgangResource::collection(
            Jahrgang::orderedWithKlassenOrdered()
        )->collection->groupBy('stufe');

        $klassen = KlasseResource::collection(
            Klasse::notBelongingToJahrgangOrdered()
        );

        return response()->json(['jahrgaenge' => $jahrgaenge, 'klassen' => $klassen], Response::HTTP_OK);
    }

    public function update(): JsonResponse
    {
        $only = [
            'editable_teilnoten',
            'editable_noten',
            'editable_mahnungen',
            'editable_fehlstunden',
            'toggleable_fehlstunden',
            'editable_fb',
            'editable_asv',
            'editable_aue',
            'editable_zb',
        ];

        collect(request()->klassen)->each(
            fn (array $klasse) =>
            Klasse::find($klasse['id'])->update(Arr::only($klasse, $only))
        );

        return response()->json(status: Response::HTTP_NO_CONTENT);
    }

}
