<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FehlstundenLeistungsGesamtRequest;
use App\Http\Requests\FehlstundenLeistungsUnentschuldigtRequest;
use App\Models\Leistung;
use Symfony\Component\HttpFoundation\Response;

class Fehlstunden extends Controller
{
    public function fehlstundenLeistungGesamt(
		FehlstundenLeistungsGesamtRequest $request,
		Leistung $leistung,
	): Response {
        $leistung->update(attributes: [
			'fehlstundenGesamt' => $request->get(key: 'value'),
			'tsFehlstundenGesamt' => now()->format(format: 'Y-m-d H:i:s.u'),
		]);

		return response(status: Response::HTTP_NO_CONTENT);
    }

    public function fehlstundenLeistungUnentschuldigt(
		FehlstundenLeistungsUnentschuldigtRequest $request,
		Leistung $leistung,
	): Response {
        $leistung->update(attributes: [
			'fehlstundenUnentschuldigt' => $request->get(key: 'value'),
			'tsFehlstundenUnentschuldigt' => now()->format(format: 'Y-m-d H:i:s.u'),
		]);

		return response(status: Response::HTTP_NO_CONTENT);
    }
}