<?php

namespace Tests\Feature\Import;

use App\Models\Jahrgang;
use App\Models\Klasse;
use App\Models\Lernabschnitt;
use App\Services\DataImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImportLernabschnitteTest extends TestCase
{
    use RefreshDatabase;

    public const TABLE = 'lernabschnitte';

    private function data(): array
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
                    }
                }
            ]
        }', true);
    }

    /** Init data */
    private function initData(): void
    {
        Jahrgang::factory()->create(['id' => 1]);
        Klasse::factory()->create(['id' => 1]);
    }

    /** It creates */
    public function test_it_creates(): void
    {
        $this->initData();
        $data = $this->data();

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, [
                'id' => '55867',
                'fehlstundenGesamt' => null,
                'tsFehlstundenGesamt' => '2023-03-28 07:00:19.031',
                'fehlstundenGesamtUnentschuldigt' => null,
                'tsFehlstundenGesamtUnentschuldigt' => '2023-03-28 07:00:19.031',
                'pruefungsordnung' => 'APO-GOSt(B)10/G8',
                'lernbereich1note' => null,
                'lernbereich2note' => null,
                'foerderschwerpunkt1' => null,
                'foerderschwerpunkt2' => null,
            ]);
    }

    /** It does not update */
    public function test_it_does_not_update(): void
    {
        $this->initData();
        $data = $this->data();

        Lernabschnitt::factory()->create([
            'id' => 55867,
            'pruefungsordnung' => 'Initial',
        ]);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, ['pruefungsordnung' => 'Initial'])
            ->assertDatabaseMissing(self::TABLE, ['pruefungsordnung' => 'APO-GOSt(B)10/G8']);
    }

    /** It does not create with missing ID */
    public function test_it_does_not_create_with_missing_id(): void
    {
        $this->initData();

        $data = $this->data();
        unset($data['schueler'][0]['lernabschnitt']['id']);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with empty ID */
    public function test_it_does_not_create_with_empty_id(): void
    {
        $this->initData();

        $data = $this->data();
        $data['schueler'][0]['lernabschnitt']['id'] = '';

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with null ID */
    public function test_it_does_not_create_with_null_id(): void
    {
        $this->initData();

        $data = $this->data();
        $data['schueler'][0]['lernabschnitt']['id'] = null;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with negative ID */
    public function test_it_does_not_create_with_negative_id(): void
    {
        $this->initData();

        $data = $this->data();
        $data['schueler'][0]['lernabschnitt']['id'] = -1;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with non numeric ID */
    public function test_it_does_not_create_with_non_numeric_id(): void
    {
        $this->initData();

        $data = $this->data();
        $data['schueler'][0]['lernabschnitt']['id'] = 'x';

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with zero ID */
    public function test_it_does_not_create_with_zero_id(): void
    {
        $this->initData();

        $data = $this->data();
        $data['schueler'][0]['lernabschnitt']['id'] = 0;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It creates with "fehlstundenGesamt" null  */
    public function test_it_creates_with_fehlstungen_gesamt_null(): void
    {
        $this->initData();

        $data = $this->data();
        $data['schueler'][0]['lernabschnitt']['fehlstundenGesamt'] = null;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1);
    }
}
