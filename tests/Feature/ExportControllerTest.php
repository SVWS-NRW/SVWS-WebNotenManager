<?php

namespace Tests\Feature;

use App\Models\Bemerkung;
use App\Models\Leistung;
use App\Models\Lernabschnitt;
use App\Models\Schueler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExportControllerTest extends TestCase
{
	use RefreshDatabase;

	private string $url = 'api.export';

    public function test_export_for_everyone()
    {
		Schueler::factory()
			->count(count: 3)
			->has(factory: Leistung::factory()->count(count: 3), relationship: 'leistungen')
			->has(factory: Lernabschnitt::factory())
			->has(factory: Bemerkung::factory())
			->create();

        $response = $this->getJson(uri: route(name: 'api.export'));

        $response->assertOk()
			->assertJsonCount(count: 3)
			->assertJsonCount(count: 3, key: '0.leistungsdaten')
			->assertJsonStructure(structure: [
				'*' => [
					'id',
					'leistungsdaten' => [
						'*' => [
							'id', 'note', 'tsNote', 'fehlstundenGesamt', 'tsFehlstundenGesamt',
							'fehlstundenUnentschuldigt', 'tsFehlstundenUnentschuldigt', 'fachbezogeneBemerkungen',
							'tsFachbezogeneBemerkungen', 'istGemahnt', 'tsIstGemahnt',
						],
					],
					'lernabschnitt' => [
						'id', 'fehlstundenGesamt', 'tsFehlstundenGesamt', 'fehlstundenUnentschuldigt',
						'tsFehlstundenUnentschuldigt', 'lernbereich1note', 'lernbereich2note', 'foerderschwerpunkt1',
						'foerderschwerpunkt2',
					],
					'bemerkungen' => [
						'ASV', 'tsASV', 'AUE', 'tsAUE', 'ZB', 'tsZB', 'LELS', 'schulformEmpf',
						'individuelleVersetzungsbemerkungen', 'tsIndividuelleVersetzungsbemerkungen',
						'foerderbemerkungen',
					],
				],
			]
		);
    }
}
