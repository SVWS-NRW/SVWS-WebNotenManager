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
	// TODO: Add tests

	/**
	 * @throws AuthorizationException
	 */
	public function fs(FsRequest $request, Leistung $leistung, MatrixSettings $settings): Response
	{
		$this->authorize(
			ability: 'update',
			arguments: [$leistung, $settings],
		);

        $leistung->update(attributes: [
			'fehlstundenFach' => $request->get(key: 'value'),
			'tsFehlstundenFach' => now()->format(format: 'Y-m-d H:i:s.u'),
		]);

		return response(status: Response::HTTP_NO_CONTENT);
    }

	/**
	 * @throws AuthorizationException
	 */
	public function fsu(FsuRequest $request, Leistung $leistung, MatrixSettings $settings): Response
	{
		$this->authorize(
			ability: 'update',
			arguments: [$leistung, $settings],
		);

        $leistung->update(attributes: [
			'fehlstundenUnentschuldigtFach' => $request->get(key: 'value'),
			'tsFehlstundenUnentschuldigtFach' => now()->format(format: 'Y-m-d H:i:s.u'),
		]);

		return response(status: Response::HTTP_NO_CONTENT);
    }

	/**
	 * @throws AuthorizationException
	 */
	public function gfs(GfsRequest $request, Schueler $schueler): Response
	{
		$this->authorize(
			ability: 'update',
			arguments: $schueler,
		);

		$schueler->lernabschnitt->update(attributes: [
			'fehlstundenGesamt' => $request->get(key: 'value'),
			'tsFehlstundenGesamt' => now()->format(format: 'Y-m-d H:i:s.u'),
		]);

		return response(status: Response::HTTP_NO_CONTENT);
    }

	/**
	 * @throws AuthorizationException
	 */
	public function gfsu(GfsuRequest $request, Schueler $schueler): Response
	{
		$this->authorize(
			ability: 'update',
			arguments: $schueler,
		);

		Lernabschnitt::whereBelongsTo(related: $schueler)->first()->update(attributes: [
			'fehlstundenGesamtUnentschuldigt' => $request->get(key: 'value'),
			'tsFehlstundenGesamtUnentschuldigt' => now()->format(format: 'Y-m-d H:i:s.u'),
		]);

		return response(status: Response::HTTP_NO_CONTENT);
    }
}