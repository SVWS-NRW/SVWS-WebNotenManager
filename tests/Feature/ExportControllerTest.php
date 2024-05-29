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

    /**
     * Endpoint URL
     *
     * @var string
     */
    private string $url = 'api.export';

    /**
     * Test if export for everyone
     *
     * @return void
     */
    public function test_export_for_everyone(): void
    {
		Schueler::factory()
			->count(3)
			->has(Leistung::factory()->count(3), 'leistungen')
			->has(Lernabschnitt::factory())
			->has(Bemerkung::factory())
			->create();

        $this->getJson(route('api.export'))->assertOk()
			->assertJsonCount(3)
			->assertJsonCount(3, '0.leistungsdaten')
			->assertJsonStructure([
				'*' => [
					'id',
					'leistungsdaten' => [
						'*' => [
							'id', 'note', 'tsNote', 'fehlstundenFach', 'tsFehlstundenFach',
							'fehlstundenUnentschuldigtFach', 'tsFehlstundenUnentschuldigtFach', 'fachbezogeneBemerkungen',
							'tsFachbezogeneBemerkungen', 'istGemahnt', 'tsIstGemahnt',
						],
					],
					'lernabschnitt' => [
						'id', 'fehlstundenGesamt', 'tsFehlstundenGesamt', 'fehlstundenGesamtUnentschuldigt',
						'tsFehlstundenGesamtUnentschuldigt', 'lernbereich1note', 'lernbereich2note', 'foerderschwerpunkt1',
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
