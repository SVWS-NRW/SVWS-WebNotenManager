<?php

namespace Tests\Feature\Import;

use App\Models\{Jahrgang, Klasse, Lerngruppe, Note};
use App\Models\Leistung;
use App\Models\Schueler;
use App\Models\Teilleistung;
use App\Models\Teilleistungsart;
use App\Services\DataImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImportLeistungsdatenTest extends TestCase
{
    use RefreshDatabase;

    public const TABLE = 'leistungen';

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
                            "mahndatum": null
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
        Lerngruppe::factory()->create();
        Jahrgang::factory()->create();
        Klasse::factory()->create();

        (new DataImportService($this->data()))->execute();
        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, [
                'id' => 842744,
            ]);
    }

    /**
     * It does not create with missing id
     *
     * @return void
     */
    public function test_it_does_not_create_with_missing_id(): void
    {
        Jahrgang::factory()->create();
        Klasse::factory()->create();

        $data = $this->data();
        unset($data['schueler'][0]['leistungsdaten'][0]['id']);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not create with empty id
     *
     * @return void
     */
    public function test_it_does_not_create_with_empty_id(): void
    {
        Jahrgang::factory()->create();
        Klasse::factory()->create();

        $data = $this->data();
        $data['schueler'][0]['leistungsdaten'][0]['id'] = '';

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not create with null id
     *
     * @return void
     */
    public function test_it_does_not_create_with_null_id(): void
    {
        Jahrgang::factory()->create();
        Klasse::factory()->create();

        $data = $this->data();
        $data['schueler'][0]['leistungsdaten'][0]['id'] = null;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not create with negative id
     *
     * @return void
     */
    public function test_it_does_not_create_with_negative_id(): void
    {
        Jahrgang::factory()->create();
        Klasse::factory()->create();

        $data = $this->data();
        $data['schueler'][0]['leistungsdaten'][0]['id'] = -1;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not create with zero id
     *
     * @return void
     */
    public function test_it_does_not_create_with_zero_id(): void
    {
        Jahrgang::factory()->create();
        Klasse::factory()->create();

        $data = $this->data();
        $data['schueler'][0]['leistungsdaten'][0]['id'] = 0;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not create with non-numeric id
     *
     * @return void
     */
    public function test_it_does_not_create_with_non_numeric_id(): void
    {
        Jahrgang::factory()->create();
        Klasse::factory()->create();

        $data = $this->data();
        $data['schueler'][0]['leistungsdaten'][0]['id'] = "X";

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }
    /**
     * It does not create with "lerngruppenID" missing
     *
     * @return void
     */
    public function test_it_does_not_create_with_lerngruppe_missing(): void
    {
        Jahrgang::factory()->create();
        Klasse::factory()->create();

        $data = $this->data();
        unset($data['schueler'][0]['leistungsdaten'][0]['lerngruppenID']);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not create with "lerngruppenID" empty
     *
     * @return void
     */
    public function test_it_does_not_create_with_lerngruppe_empty(): void
    {
        Jahrgang::factory()->create();
        Klasse::factory()->create();

        $data = $this->data();
        $data['schueler'][0]['leistungsdaten'][0]['lerngruppenID'] = null;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not create with non existing "Lerngruppe"
     *
     * @return void
     */
    public function test_it_does_not_create_with_non_existing_lerngruppe(): void
    {
        Lerngruppe::factory()->create(['id' => 1]); // Database ID
        Jahrgang::factory()->create();
        Klasse::factory()->create();

        $data = $this->data();
        $data['schueler'][0]['leistungsdaten'][0]['lerngruppenID'] = 2; // Different ID

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not create with note missing
     *
     * @return void
     */
    public function test_it_does_not_create_note_missing(): void
    {
        Lerngruppe::factory()->create();
        Jahrgang::factory()->create();
        Klasse::factory()->create();

        $data = $this->data();
        unset($data['schueler'][0]['leistungsdaten'][0]['note']);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It creates with empty note
     *
     * @return void
     */
    public function test_it_creates_with_empty_note(): void
    {
        Lerngruppe::factory()->create();
        Jahrgang::factory()->create();
        Klasse::factory()->create();

        $data = $this->data();
        $data['schueler'][0]['leistungsdaten'][0]['note'] = '';

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1);
    }

    /**
     * It creates with empty note
     *
     * @return void
     */
    public function test_it_creates_with_null_note(): void
    {
        Lerngruppe::factory()->create();
        Jahrgang::factory()->create();
        Klasse::factory()->create();

        $data = $this->data();
        $data['schueler'][0]['leistungsdaten'][0]['note'] = null;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1);
    }

    /**
     * It does not create with non existing note
     *
     * @return void
     */
    public function test_it_does_not_create_with_non_existing_note(): void
    {
        Lerngruppe::factory()->create();
        Jahrgang::factory()->create();
        Klasse::factory()->create();
        Note::factory()->create(['kuerzel' => '1']); // Database "Note"

        $data = $this->data();
        $data['schueler'][0]['leistungsdaten'][0]['note'] = '2'; // Different "Note"

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It updates
     *
     * @return void
     */
    public function test_it_updates(): void
    {
        Lerngruppe::factory()->create();
        Jahrgang::factory()->create();
        Klasse::factory()->create();

        $oldNote = Note::factory()->create(['kuerzel' => 'old']);
        $newNote = Note::factory()->create(['kuerzel' => 'new']);
        $schueler = Schueler::factory()->create(['id' => 3415]);

        $leistung = Leistung::factory()->for($schueler)->create([
            'id' => 842744,
            'note_id' => $oldNote->id,
        ]);

        $data = $this->data();
        $data['schueler'][0]['id'] = $schueler->id;
        $data['schueler'][0]['leistungsdaten'][0]['note'] = $newNote->kuerzel;
        $data['schueler'][0]['leistungsdaten'][0]['tsNote'] = now();
        $data['schueler'][0]['leistungsdaten'][0]['abiturfach'] = 'not updateable';

        $ow = new DataImportService($data);
        $ow->execute();

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, [
                'id' => 842744,
                'schueler_id' => $schueler->id,
                'note_id' => $newNote->id,
                'abiturfach' => null,
            ]);
    }
}
