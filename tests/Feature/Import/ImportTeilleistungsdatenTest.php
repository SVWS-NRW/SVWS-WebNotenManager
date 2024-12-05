<?php

namespace Tests\Feature\Import;

use App\Models\{Jahrgang, Klasse, Lerngruppe, Note, Teilleistung, Teilleistungsart};
use App\Services\DataImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImportTeilleistungsdatenTest extends TestCase
{
    use RefreshDatabase;

    public const TABLE = 'teilleistungen';

    /**
     * Import data
     *
     * @return array
     */
    private function data(): array
    {
        return json_decode('{
            "schueler": [
                {
                    "id": 3415,
                    "jahrgangID": 1,
                    "klasseID": 1,
                    "nachname": "Huiß",
                    "vorname": "Vanessa",
                    "geschlecht": "w",
                    "bilingualeSprache": null,
                    "istZieldifferent": false,
                    "istDaZFoerderung": false,
                    "sprachenfolge": [],
                    "lernabschnitt": {},
                    "leistungsdaten": [
                        {
                            "id": 842744,
                            "lerngruppenID": 1,
                            "note": "",
                            "tsNote": "2023-03-28 07:00:19.014",
                            "noteQuartal": "",
                            "tsNoteQuartal": "2023-05-23 01:23:39.755",
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
                            "teilleistungen": [
                                {
                                    "id": 1,
                                    "artID": 1,
                                    "tsArtID": "2002-02-02 02:02:02.000",
                                    "datum": "2002-02-02",
                                    "tsDatum": "2002-02-02 02:02:02.000",
                                    "bemerkung": "toll",
                                    "tsBemerkung": "2002-02-02 02:02:02.000",
                                    "note": "1",
                                    "tsNote": "2002-02-02 02:02:02.000"
                                }
                            ]
                        }
                    ]
                }
            ]
        }', true);
    }

    /** It creates */
    public function test_it_creates(): void
    {
        $this->init();
        (new DataImportService($this->data()))->execute();

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, ['id' => 1]);
    }

    /** It does not create with missing id */
    public function test_it_does_not_create_with_missing_id(): void
    {
        $this->init();

        $data = $this->data();
        unset($data['schueler'][0]['leistungsdaten'][0]['teilleistungen'][0]['id']);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with empty id */
    public function test_it_does_not_create_with_empty_id(): void
    {
        $this->init();

        $data = $this->data();
        $data['schueler'][0]['leistungsdaten'][0]['teilleistungen'][0]['id'] = null;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with non-numeric id */
    public function test_it_does_not_create_with_non_numeric_id(): void
    {
        $this->init();

        $data = $this->data();
        $data['schueler'][0]['leistungsdaten'][0]['teilleistungen'][0]['id'] = "x";

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with existing id */
    public function test_it_does_not_create_with_existing_id(): void
    {
        $this->init();

        Teilleistung::factory()->create(['id' => 1]);

        (new DataImportService($this->data()))->execute();

        $this->assertDatabaseCount(self::TABLE, 1);
    }

    /** It does not create with missing artID */
    public function test_it_does_not_create_with_missing_artid(): void
    {
        $this->init();

        $data = $this->data();
        unset($data['schueler'][0]['leistungsdaten'][0]['teilleistungen'][0]['artID']);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with empty artID */
    public function test_it_does_not_create_with_empty_artid(): void
    {
        $this->init();
        $data = $this->data();
        $data['schueler'][0]['leistungsdaten'][0]['teilleistungen'][0]['artID'] = null;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with non-numeric artID */
    public function test_it_does_not_create_with_non_numeric_artid(): void
    {
        $this->init();
        $data = $this->data();
        $data['schueler'][0]['leistungsdaten'][0]['teilleistungen'][0]['artID'] = "x";

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with non-existing "Teilleistungsart" */
    public function test_it_does_not_create_with_non_existing_teilleistungsart(): void
    {
        $this->init();
        $data = $this->data();
        $data['schueler'][0]['leistungsdaten'][0]['teilleistungen'][0]['artID'] = 2;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It updates "Teilleistungsart" */
    public function test_it_updates_teilleistungsart(): void
    {
        $this->init();
        $teilleistungsart = Teilleistungsart::factory()->create(['id' => 2]);

        Teilleistung::factory()->create([
            'id' => 1,
            'teilleistungsart_id' => 1,
            'tsArtID' => "2001-01-01 01:00:00.000",
        ]);

        $data = $this->data();
        $data['schueler'][0]['leistungsdaten'][0]['teilleistungen'][0]['artID'] = 2;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, [
                'id' => 1,
                'teilleistungsart_id' => 2,
            ]);
    }

    /** It does not update "Teilleistungsart" if timestamp is before current one */
    public function test_it_does_not_update_teilleistungsart_if_timestamp_is_before_current_one(): void
    {
        $this->init();

        Teilleistung::factory()->create(['id' => 1, 'teilleistungsart_id' => 1, 'tsArtID' => now()]);

        $data = $this->data();
        $data['schueler'][0]['leistungsdaten'][0]['teilleistungen'][0]['artID'] = 2;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, [
                'id' => 1,
                'teilleistungsart_id' => 1,
                'tsArtID' => now(),
            ]);
    }

    /** It does not create with missing "tsArtID" */
    public function test_it_does_not_create_with_missing_tsartid(): void
    {
        $this->init();

        $data = $this->data();
        unset($data['schueler'][0]['leistungsdaten'][0]['teilleistungen'][0]['tsArtID']);

        (new DataImportService($data))->execute();
        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with empty "tsArtID" */
    public function test_it_does_not_create_with_empty_tsartid(): void
    {
        $this->init();

        $data = $this->data();
        $data['schueler'][0]['leistungsdaten'][0]['teilleistungen'][0]['tsArtID'] = null;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with missing "Note" */
    public function test_it_does_not_create_with_missing_note(): void
    {
        $this->init();

        $data = $this->data();
        unset($data['schueler'][0]['leistungsdaten'][0]['teilleistungen'][0]['note']);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It creates with empty "Note" */
    public function test_it_creates_with_empty_note(): void
    {
        $this->init();

        $data = $this->data();
        $data['schueler'][0]['leistungsdaten'][0]['teilleistungen'][0]['note'] = null;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1);
    }

    /** It does not create with non-existing "Note" */
    public function test_it_does_not_create_with_non_existing_note(): void
    {
        $this->init();

        $data = $this->data();
        $data['schueler'][0]['leistungsdaten'][0]['teilleistungen'][0]['note'] = '2';

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It updates "Note" */
    public function test_it_updates_note(): void
    {
        $this->init();

        Teilleistung::factory()->create([
            'id' => 1,
            'note_id' => null,
            'tsNote' => now()->startOfCentury(),
        ]);

        $data = $this->data();
        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, [
                'id' => 1,
                'note_id' => 1,
                'tsNote' => '2002-02-02 02:02:02.000000',
            ]);
    }

    /** It does not update "Note" if timestamp is before current one */
    public function test_it_does_not_update_note_if_timestamp_is_before_current_one(): void
    {
        $this->init();

        Teilleistung::factory()->create([
            'id' => 1,
            'note_id' => null,
            'tsNote' => "2003-01-01 01:00:00.000",
        ]);

        $data = $this->data();
        $data['schueler'][0]['leistungsdaten'][0]['teilleistungen'] = json_decode('[
            {
                "id": 1,
                "artID": 1,
                "tsArtID": "2024-05-23 01:23:40.644",
                "datum": "2022-11-18",
                "tsDatum": "2024-05-23 01:23:40.644",
                "bemerkung": "toll",
                "tsBemerkung": "2024-05-23 01:23:40.644",
                "note": "1",
                "tsNote": "2002-01-01 01:00:00.000"
            }
        ]', true);
        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, [
                'id' => 1,
                'note_id' => null,
            ]);
    }

    /** It does not create with missing "tsNote" */
    public function test_it_does_not_create_with_missing_tsnote(): void
    {
        $this->init();

        $data = $this->data();
        unset($data['schueler'][0]['leistungsdaten'][0]['teilleistungen'][0]['tsNote']);

        (new DataImportService($data))->execute();
        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with empty "tsNote" */
    public function test_it_does_not_create_with_empty_tsnote(): void
    {
        $this->init();

        $data = $this->data();
        $data['schueler'][0]['leistungsdaten'][0]['teilleistungen'][0]['tsNote'] = null;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with missing "Bemerkung" */
    public function test_it_does_not_create_with_missing_bemerkung(): void
    {
        $this->init();

        $data = $this->data();
        unset($data['schueler'][0]['leistungsdaten'][0]['teilleistungen'][0]['bemerkung']);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It creates with empty "Bemerkung" */
    public function test_it_creates_with_empty_bemerkung(): void
    {
        $this->init();

        $data = $this->data();
        $data['schueler'][0]['leistungsdaten'][0]['teilleistungen'][0]['bemerkung'] = null;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1);
    }

    /** It updates "Bemerkung" */
    public function test_it_updates_bemerkung(): void
    {
        $this->init();

        Teilleistung::factory()->create([
            'id' => 1,
            'bemerkung' => null,
            'tsBemerkung' => now()->startOfCentury(),
        ]);

        $data = $this->data();
        $data['schueler'][0]['leistungsdaten'][0]['teilleistungen'][0]['bemerkung'] = 'Toll';
        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, [
                'id' => 1,
                'bemerkung' => 'Toll',
                'tsBemerkung' => '2002-02-02 02:02:02.000000',
            ]);
    }

    /** It does not update "Bemerkung" if timestamp is before current one */
    public function test_it_does_not_update_bemerkung_if_timestamp_is_before_current_one(): void
    {
        $this->init();

        Teilleistung::factory()->create([
            'id' => 1,
            'bemerkung' => null,
            'tsBemerkung' => now(),
        ]);

        $data = $this->data();
        $data['schueler'][0]['leistungsdaten'][0]['teilleistungen'][0]['bemerkung'] = 'toll';
        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, [
                'id' => 1,
                'note_id' => null,
                'tsBemerkung' => now(),
            ]);
    }

    /** It does not create with missing "tsBemerkung" */
    public function test_it_does_not_create_with_missing_tsbemerkung(): void
    {
        $this->init();

        $data = $this->data();
        unset($data['schueler'][0]['leistungsdaten'][0]['teilleistungen'][0]['tsBemerkung']);

        (new DataImportService($data))->execute();
        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with empty "tsBemerkung" */
    public function test_it_does_not_create_with_empty_tsbemerkung(): void
    {
        $this->init();

        $data = $this->data();
        $data['schueler'][0]['leistungsdaten'][0]['teilleistungen'][0]['tsBemerkung'] = null;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with missing "Datum" */
    public function test_it_does_not_create_with_missing_datum(): void
    {
        $this->init();

        $data = $this->data();
        unset($data['schueler'][0]['leistungsdaten'][0]['teilleistungen'][0]['datum']);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with empty "Datum" */
    public function test_it_does_not_create_with_empty_datum(): void
    {
        $this->init();

        $data = $this->data();
        $data['schueler'][0]['leistungsdaten'][0]['teilleistungen'][0]['datum'] = null;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It updates "Datum" */
    public function test_it_updates_datum(): void
    {
        $this->init();

        Teilleistung::factory()->create([
            'id' => 1,
            'datum' => '2001-01-01',
            'tsDatum' => now()->startOfCentury(),
        ]);

        $data = $this->data();
        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, [
                'id' => 1,
                'datum' => '2002-02-02',
                'tsDatum' => '2002-02-02 02:02:02.000000',
            ]);
    }

    /** It does not update "Datum" if timestamp is before current one */
    public function test_it_does_not_update_datum_if_timestamp_is_before_current_one(): void
    {
        $this->init();

        Teilleistung::factory()->create([
            'id' => 1,
            'datum' => '2001-01-01',
            'tsBemerkung' => now(),
        ]);

        $data = $this->data();
        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, [
                'id' => 1,
                'datum' => '2001-01-01',
                'tsBemerkung' => now(),
            ]);
    }

    /** It does not create with missing "tsDatum" */
    public function test_it_does_not_create_with_missing_tsdatum(): void
    {
        $this->init();

        $data = $this->data();
        unset($data['schueler'][0]['leistungsdaten'][0]['teilleistungen'][0]['tsDatum']);

        (new DataImportService($data))->execute();
        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with empty "tsDatum" */
    public function test_it_does_not_create_with_empty_tsdatum(): void
    {
        $this->init();

        $data = $this->data();
        $data['schueler'][0]['leistungsdaten'][0]['teilleistungen'][0]['tsDatum'] = null;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** Initialize models */
    private function init(): void
    {
        Lerngruppe::factory()->create();
        Jahrgang::factory()->create();
        Klasse::factory()->create();
        Teilleistungsart::factory()->create(['id' => 1]);
        Note::factory()->create(['kuerzel' => '1']);
    }

}
