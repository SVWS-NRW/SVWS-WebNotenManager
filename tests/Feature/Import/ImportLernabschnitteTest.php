<?php

namespace Tests\Feature\Import;

use App\Models\Jahrgang;
use App\Models\Klasse;
use App\Services\DataImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImportLernabschnitteTest extends TestCase
{
    use RefreshDatabase;

    public const TABLE = 'lernabschnitte';

    private function getData(): array
    {
        return json_decode('{
            "schueler": [
                {
                    "id": 1,
                    "jahrgangID": 1,
                    "klasseID": 1,
                    "nachname": "Huiß",
                    "vorname": "Vanessa",
                    "geschlecht": "w",
                    "bilingualeSprache": null,
                    "istZieldifferent": false,
                    "istDaZFoerderung": false,
                    "sprachenfolge": [],
                    "lernabschnitt": {
                        "id": 55867,
                        "fehlstundenGesamt": null,
                        "tsFehlstundenGesamt": "2023-03-28 07:00:19.031",
                        "fehlstundenGesamtUnentschuldigt": null,
                        "tsFehlstundenGesamtUnentschuldigt": "2023-03-28 07:00:19.031",
                        "pruefungsordnung": "APO-GOSt(B)10/G8",
                        "lernbereich1note": null,
                        "lernbereich2note": null,
                        "foerderschwerpunkt1": null,
                        "foerderschwerpunkt2": null
                    },
                    "leistungsdaten": [
                        {
                            "id": 842744,
                            "lerngruppenID": 1,
                            "note": "",
                            "tsNote": "2023-03-28 07:00:19.014",
                            "istSchriftlich": true,
                            "abiturfach": null,
                            "fehlstundenFach": 0,
                            "tsFehlstundenFach": "2023-03-28 07:00:19.014",
                            "fehlstundenUnentschuldigtFach": 0,
                            "tsFehlstundenUnentschuldigtFach": "2023-03-28 07:00:19.014",
                            "fachbezogeneBemerkungen": null,
                            "tsFachbezogeneBemerkungen": "2023-03-28 07:00:19.014",
                            "neueZuweisungKursart": null,
                            "istGemahnt": false,
                            "tsIstGemahnt": "2023-03-28 07:00:19.014",
                            "mahndatum": null,
                            "teilleistungen": []
                        }
                    ]
                }
            ]
        }', true);
    }

    /**
     * It creates
     *
     * @return void
     */
    public function test_it_creates(): void
    {
        $jahrgang = Jahrgang::factory()->create(['id' => 1]);
        Klasse::factory()->create(['id' => 1, 'idJahrgang' => $jahrgang->id]);

        $data = $this->getData();

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, [
                'pruefungsordnung' => 'APO-GOSt(B)10/G8',
            ]);
    }
}
