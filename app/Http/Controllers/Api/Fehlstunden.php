<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Fehlstunden as FehlstundenRequest;
use App\Models\Leistung;
use App\Models\Lernabschnitt;
use App\Models\Schueler;
use Symfony\Component\HttpFoundation\Response;

class Fehlstunden extends Controller
{
    public function fehlstundenLeistungGesamt(FehlstundenRequest\FachRequest $request, Leistung $leistung): Response {
        $leistung->update(attributes: [
			'fehlstundenFach' => $request->get(key: 'value'),
			'tsFehlstundenFach' => now()->format(format: 'Y-m-d H:i:s.u'),
		]);

		return response(status: Response::HTTP_NO_CONTENT);
    }

    public function fehlstundenLeistungUnentschuldigt(
		FehlstundenRequest\UnentschuldigtFachRequest $request,
		Leistung $leistung,
	): Response {
        $leistung->update(attributes: [
			'fehlstundenUnentschuldigtFach' => $request->get(key: 'value'),
			'tsFehlstundenUnentschuldigtFach' => now()->format(format: 'Y-m-d H:i:s.u'),
		]);

		return response(status: Response::HTTP_NO_CONTENT);
    }

    public function fehlstundenSchuelerGesamt(FehlstundenRequest\GesamtRequest $request, Schueler $schueler): Response {
		$schueler->lernabschnitt->update(attributes: [
			'fehlstundenGesamt' => $request->get(key: 'value'),
			'tsFehlstundenGesamt' => now()->format(format: 'Y-m-d H:i:s.u'),
		]);

		return response(status: Response::HTTP_NO_CONTENT);
    }

    public function fehlstundenSchuelerGesamtUnentschuldigt(
		FehlstundenRequest\GesamtUnentschuldigtRequest $request,
		Schueler $schueler,
	): Response {
		Lernabschnitt::whereBelongsTo(related: $schueler)->first()->update(attributes: [
			'fehlstundenGesamtUnentschuldigt' => $request->get(key: 'value'),
			'tsFehlstundenGesamtUnentschuldigt' => now()->format(format: 'Y-m-d H:i:s.u'),
		]);

		return response(status: Response::HTTP_NO_CONTENT);
    }
}