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
use App\Http\Resources\Export\SchuelerResource;
use App\Http\Resources\Export\TeilleistungsartResource;
use App\Models\Daten;
use App\Models\Fach;
use App\Models\Floskelgruppe;
use App\Models\Foerderschwerpunkt;
use App\Models\Jahrgang;
use App\Models\Klasse;
use App\Models\Note;
use App\Models\Schueler;
use App\Models\Teilleistungsart;
use App\Models\User;
use Debugbar;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DataExportService
{
	public function export(): AnonymousResourceCollection
	{
		$schueler = Schueler::query()
			->with([
				'leistungen' => [
					'note',
				],
				'lernabschnitt' => [
					'lernbereich1Note', 'lernbereich2Note', 'foerderschwerpunkt1Relation', 'foerderschwerpunkt2Relation',
				],
			])
			->get();

		return SchuelerResource::collection($schueler);
	}
}