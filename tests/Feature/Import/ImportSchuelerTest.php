<?php

namespace Tests\Feature\Import;

use App\Models\{Klasse, Jahrgang};
use App\Services\DataImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImportSchuelerTest extends TestCase
{
    use RefreshDatabase;

    public const TABLE = 'schueler';

    /**
     * Create data
     *
     * @return array
     */
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
                    "lernabschnitt": {
                        "id": 55867,
                        "fehlstundenGesamt": null,
                        "tsFehlstundenGesamt": "2024-05-23 01:23:39.775",
                        "fehlstundenGesamtUnentschuldigt": null,
                        "tsFehlstundenGesamtUnentschuldigt": "2024-05-23 01:23:39.775",
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

    /** It creates  */
    public function test_it_creates(): void
    {
        $jahrgang = Jahrgang::factory()->create();
        Klasse::factory()->create(['idJahrgang' => $jahrgang->id]);

        (new DataImportService($this->getData()))->execute();

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, ['id' => 1, 'jahrgang_id' => 1, 'klasse_id' => 1]);
    }

    /** It does not create with missing "klasseID" */
    public function test_it_does_not_create_with_missing_klasseId(): void
    {
        $jahrgang = Jahrgang::factory()->create();
        Klasse::factory()->create(['idJahrgang' => $jahrgang->id]);

        $data = $this->getData();
        unset($data['schueler'][0]['klasseID']);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with null "klasseID" */
    public function test_it_does_not_create_with_null_klasseId(): void
    {
        $jahrgang = Jahrgang::factory()->create();
        Klasse::factory()->create(['idJahrgang' => $jahrgang->id]);

        $data = $this->getData();
        $data['schueler'][0]['klasseID'] = null;

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with empty "klasseID" */
    public function test_it_does_not_create_with_empty_klasseId(): void
    {
        $jahrgang = Jahrgang::factory()->create();
        Klasse::factory()->create(['idJahrgang' => $jahrgang->id]);

        $data = $this->getData();
        $data['schueler'][0]['klasseID'] = '';

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with negative "klasseID" */
    public function test_it_does_not_create_with_negative_klasseId(): void
    {
        $jahrgang = Jahrgang::factory()->create();
        Klasse::factory()->create(['idJahrgang' => $jahrgang->id]);

        $data = $this->getData();
        $data['schueler'][0]['klasseID'] = -1;

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with non integer "klasseID" */
    public function test_it_does_not_create_with_non_integer_klasseId(): void
    {
        $jahrgang = Jahrgang::factory()->create();
        Klasse::factory()->create(['idJahrgang' => $jahrgang->id]);

        $data = $this->getData();
        $data['schueler'][0]['klasseID'] = 'X';

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with non existing related "Klasse" */
    public function test_it_does_not_create_with_non_existing_related_klasse(): void
    {
        $jahrgang = Jahrgang::factory()->create();
        Klasse::factory()->create(['idJahrgang' => $jahrgang->id]);

        $data = $this->getData();
        $data['schueler'][0]['klasseID'] = 2;

        new DataImportService($data);
        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with missing "jahrgangID" */
    public function test_it_does_not_create_with_missing_jahrgangId(): void
    {
        $jahrgang = Jahrgang::factory()->create();
        Klasse::factory()->create(['idJahrgang' => $jahrgang->id]);

        $data = $this->getData();
        unset($data['schueler'][0]['jahrgangID']);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with null "jahrgangID" */
    public function test_it_does_not_create_with_null_jahrgangId(): void
    {
        $jahrgang = Jahrgang::factory()->create();
        Klasse::factory()->create(['idJahrgang' => $jahrgang->id]);

        $data = $this->getData();
        $data['schueler'][0]['jahrgangID'] = null;

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with empty "jahrgangID" */
    public function test_it_does_not_create_with_empty_jahrgangId(): void
    {
        $jahrgang = Jahrgang::factory()->create();
        Klasse::factory()->create(['idJahrgang' => $jahrgang->id]);

        $data = $this->getData();
        $data['schueler'][0]['jahrgangID'] = '';

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with negative "Jahrgang" */
    public function test_it_does_not_create_with_negative_jahrgangId(): void
    {
        $jahrgang = Jahrgang::factory()->create();
        Klasse::factory()->create(['idJahrgang' => $jahrgang->id]);

        $data = $this->getData();
        $data['schueler'][0]['jahrgangID'] = -1;

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with non integer "Jahrgang" */
    public function test_it_does_not_create_with_non_integer_jahrgangId(): void
    {
        $jahrgang = Jahrgang::factory()->create();
        Klasse::factory()->create(['idJahrgang' => $jahrgang->id]);

        $data = $this->getData();
        $data['schueler'][0]['jahrgangID'] = 'X';

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with non existing related "Jahrgang" */
    public function test_it_does_not_create_with_non_existing_related_jahrgang(): void
    {
        $jahrgang = Jahrgang::factory()->create();
        Klasse::factory()->create(['idJahrgang' => $jahrgang->id]);

        $data = $this->getData();
        $data['schueler'][0]['jahrgangID'] = 2;

        new DataImportService($data);
        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with missing "Geschlecht" */
    public function test_it_does_not_create_with_missing_geschleht(): void
    {
        $jahrgang = Jahrgang::factory()->create();
        Klasse::factory()->create(['idJahrgang' => $jahrgang->id]);

        $data = $this->getData();
        unset($data['schueler'][0]['geschlecht']);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with null "Geschlecht" */
    public function test_it_does_not_create_with_null_geschleht(): void
    {
        $jahrgang = Jahrgang::factory()->create();
        Klasse::factory()->create(['idJahrgang' => $jahrgang->id]);

        $data = $this->getData();
        $data['schueler'][0]['geschlecht'] = null;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with empty "Geschlecht" */
    public function test_it_does_not_create_with_empty_geschleht(): void
    {
        $jahrgang = Jahrgang::factory()->create();
        Klasse::factory()->create(['idJahrgang' => $jahrgang->id]);

        $data = $this->getData();
        $data['schueler'][0]['geschlecht'] = '';

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It creates with proper "Geschlecht" if incorrect provided */
    public function test_it_creates_with_proper_geschlecht_if_incorrect_provided(): void
    {
        $jahrgang = Jahrgang::factory()->create();
        Klasse::factory()->create(['idJahrgang' => $jahrgang->id]);

        $data = $this->getData();
        $data['schueler'][0]['geschlecht'] = 'R'; // Incorrect "Geschlecht"

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, ['id' => 1, 'geschlecht' => 'x']);
    }
}
