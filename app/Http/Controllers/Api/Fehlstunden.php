<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Fehlstunden\FsRequest;
use App\Http\Requests\Fehlstunden\GfsRequest;
use App\Http\Requests\Fehlstunden\GfsuRequest;
use App\Http\Requests\Fehlstunden\FsuRequest;
use App\Models\Leistung;
use App\Models\Lernabschnitt;
use App\Models\Schueler;
use App\Settings\MatrixSettings;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpFoundation\Response;

class Fehlstunden extends Controller
{
	/**
	 * @throws AuthorizationException
	 */
	public function fs(FsRequest $request, Leistung $leistung, MatrixSettings $settings): Response
	{
		$this->authorize('update', [$leistung, $settings]);

        $leistung->update([
			'fehlstundenFach' => $request->get('value'),
			'tsFehlstundenFach' => now()->format('Y-m-d H:i:s.u'),
		]);

		return response(status: Response::HTTP_NO_CONTENT);
    }

	/**
	 * @throws AuthorizationException
	 */
	public function fsu(FsuRequest $request, Leistung $leistung, MatrixSettings $settings): Response
	{
		$this->authorize('update', [$leistung, $settings]);

        $leistung->update(attributes: [
			'fehlstundenUnentschuldigtFach' => $request->get('value'),
			'tsFehlstundenUnentschuldigtFach' => now()->format('Y-m-d H:i:s.u'),
		]);

        return response(status: Response::HTTP_NO_CONTENT);
    }

	/**
	 * @throws AuthorizationException
	 */
	public function gfs(GfsRequest $request, Schueler $schueler): Response
	{
		$this->authorize('update', $schueler);

		$schueler->lernabschnitt->update([
			'fehlstundenGesamt' => $request->get('value'),
			'tsFehlstundenGesamt' => now()->format('Y-m-d H:i:s.u'),
		]);

        return response(status: Response::HTTP_NO_CONTENT);
    }

	/**
	 * @throws AuthorizationException
	 */
	public function gfsu(GfsuRequest $request, Schueler $schueler): Response
	{
		$this->authorize('update', $schueler);

		Lernabschnitt::whereBelongsTo($schueler)->first()->update([
			'fehlstundenGesamtUnentschuldigt' => $request->get('value'),
			'tsFehlstundenGesamtUnentschuldigt' => now()->format('Y-m-d H:i:s.u'),
		]);

        return response(status: Response::HTTP_NO_CONTENT);
    }
}