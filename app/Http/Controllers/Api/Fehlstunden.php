<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Fehlstunden\SchuelerGesamtRequest;
use App\Http\Requests\Fehlstunden\SchuelerGesamtUnentschuldigtRequest;
use App\Http\Requests\Fehlstunden\LeistungGesamtRequest;
use App\Http\Requests\Fehlstunden\LeistungUnentschuldigtRequest;
use App\Models\Leistung;
use App\Models\Lernabschnitt;
use App\Models\Schueler;
use Symfony\Component\HttpFoundation\Response;

class Fehlstunden extends Controller
{
    public function fehlstundenLeistungGesamt(LeistungGesamtRequest $request, Leistung $leistung): Response {
        $leistung->update(attributes: [
			'fehlstundenGesamt' => $request->get(key: 'value'),
			'tsFehlstundenGesamt' => now()->format(format: 'Y-m-d H:i:s.u'),
		]);

		return response(status: Response::HTTP_NO_CONTENT);
    }

    public function fehlstundenLeistungUnentschuldigt(
		LeistungUnentschuldigtRequest $request,
		Leistung $leistung,
	): Response {
        $leistung->update(attributes: [
			'fehlstundenUnentschuldigt' => $request->get(key: 'value'),
			'tsFehlstundenUnentschuldigt' => now()->format(format: 'Y-m-d H:i:s.u'),
		]);

		return response(status: Response::HTTP_NO_CONTENT);
    }

    public function fehlstundenSchuelerGesamt(SchuelerGesamtRequest $request, Schueler $schueler): Response {
		$schueler->lernabschnitt->update(attributes: [
			'fehlstundenGesamt' => $request->get(key: 'value'),
			'tsFehlstundenGesamt' => now()->format(format: 'Y-m-d H:i:s.u'),
		]);

		return response(status: Response::HTTP_NO_CONTENT);
    }

    public function fehlstundenSchuelerGesamtUnentschuldigt(
		SchuelerGesamtUnentschuldigtRequest $request,
		Schueler $schueler,
	): Response {
		Lernabschnitt::whereBelongsTo(related: $schueler)->first()->update(attributes: [
			'fehlstundenGesamtUnentschuldigt' => $request->get(key: 'value'),
			'tsFehlstundenGesamtUnentschuldigt' => now()->format(format: 'Y-m-d H:i:s.u'),
		]);

		return response(status: Response::HTTP_NO_CONTENT);
    }
}