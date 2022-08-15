<?php

namespace App\Services;

use App\Http\Resources\Export\DatenCollection;
use App\Http\Resources\Export\DatenResource;
use App\Http\Resources\Export\FachResource;
use App\Http\Resources\Export\FoerderschwerpunkteResource;
use App\Http\Resources\Export\JahrgangResource;
use App\Http\Resources\Export\NoteResource;
use App\Http\Resources\Export\FloskelgruppeResource;
use App\Http\Resources\Export\KlasseResource;
use App\Http\Resources\Export\TeilleistungsartResource;
use App\Models\Daten;
use App\Models\Fach;
use App\Models\Floskelgruppe;
use App\Models\Foerderschwerpunkt;
use App\Models\Jahrgang;
use App\Models\Klasse;
use App\Models\Note;
use App\Models\Teilleistungsart;
use App\Models\User;
use Debugbar;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DataExportService
{
	public function export(int $id): DatenResource
	{
		$daten = Daten::query()
			->where('lehrerId', '=', $id)
			->firstOrFail();

		return new DatenResource($daten);
	}
}
