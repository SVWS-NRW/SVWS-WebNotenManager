<?php

namespace App\Http\Controllers;

use App\Http\Resources\Export\SchuelerResource;
use App\Models\Schueler;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ExportController extends Controller
{
	public function __invoke(): AnonymousResourceCollection
	{
		$schueler = Schueler::query()
			->with([
				'leistungen' => [
					'note',
				],
				'lernabschnitt' => [
					'lernbereich1Note', 'lernbereich2Note', 'foerderschwerpunkt1Relation', 'foerderschwerpunkt2Relation',
				],
				'bemerkung'
			])
			->get();

		return SchuelerResource::collection($schueler);
	}
}
