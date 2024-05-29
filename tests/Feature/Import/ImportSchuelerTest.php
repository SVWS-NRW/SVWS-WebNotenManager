<?php

namespace Tests\Feature\Import;

use App\Models\Klasse;
use App\Models\Jahrgang;
use App\Services\DataImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImportSchuelerTest extends TestCase
{
    use RefreshDatabase;

    public const TABLE = 'schueler';

    /**
     * It creates schueler with "jahrgang" and "klasse"
     *
     * @return void
     */
    public function test_it_creates_schueler_with_jahrgang_and_klasse(): void
    {
        $jahrgang = Jahrgang::factory()->create();
        Klasse::factory()->create(['idJahrgang' => $jahrgang->id]);

        $data = json_decode('{
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
                    "istDaZFoerderung": false
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, [
                'id' => 1,
                'jahrgang_id' => 1,
                'klasse_id' => 1,
            ]);

    }

    /**
     * It does not create schueler with missing "klasseID"
     *
     * @return void
     */
    public function test_it_does_not_create_schueler_with_missing_klasseId(): void
    {
        $jahrgang = Jahrgang::factory()->create();
        Klasse::factory()->create(['idJahrgang' => $jahrgang->id]);

        $data = json_decode('{
            "schueler": [
                {
                    "id": 1,
                    "jahrgangID": 1,
                    "nachname": "Huiß",
                    "vorname": "Vanessa",
                    "geschlecht": "w",
                    "bilingualeSprache": null,
                    "istZieldifferent": false,
                    "istDaZFoerderung": false
                }
            ]
        }', true);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not create schueler with empty "klasseID"
     *
     * @return void
     */
    public function test_it_does_not_create_schueler_with_empty_klasseId(): void
    {
        $jahrgang = Jahrgang::factory()->create();
        Klasse::factory()->create(['idJahrgang' => $jahrgang->id]);

        $data = json_decode('{
            "schueler": [
                {
                    "id": 1,
                    "jahrgangID": 1,
                    "klasseID": null,
                    "nachname": "Huiß",
                    "vorname": "Vanessa",
                    "geschlecht": "w",
                    "bilingualeSprache": null,
                    "istZieldifferent": false,
                    "istDaZFoerderung": false
                }
            ]
        }', true);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not create schueler with negative "klasseId"
     *
     * @return void
     */
    public function test_it_does_not_create_schueler_with_negative_klasseId(): void
    {
        $jahrgang = Jahrgang::factory()->create();
        Klasse::factory()->create(['idJahrgang' => $jahrgang->id]);

        $data = json_decode('{
            "schueler": [
                {
                    "id": 1,
                    "jahrgangID": 1,
                    "klasseID": -1,
                    "nachname": "Huiß",
                    "vorname": "Vanessa",
                    "geschlecht": "w",
                    "bilingualeSprache": null,
                    "istZieldifferent": false,
                    "istDaZFoerderung": false
                }
            ]
        }', true);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not create schueler with non integer "klasseID"
     *
     * @return void
     */
    public function test_it_does_not_create_schueler_with_non_integer_klasseId(): void
    {
        $jahrgang = Jahrgang::factory()->create();
        Klasse::factory()->create(['idJahrgang' => $jahrgang->id]);

        $data = json_decode('{
            "schueler": [
                {
                    "id": 1,
                    "jahrgangID": 1,
                    "klasseID": "X",
                    "nachname": "Huiß",
                    "vorname": "Vanessa",
                    "geschlecht": "w",
                    "bilingualeSprache": null,
                    "istZieldifferent": false,
                    "istDaZFoerderung": false
                }
            ]
        }', true);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not create schueler with non existing related "klasse"
     *
     * @return void
     */
    public function test_it_does_not_create_schueler_with_non_existing_related_klasse(): void
    {
        $jahrgang = Jahrgang::factory()->create();
        Klasse::factory()->create(['idJahrgang' => $jahrgang->id]);

        $data = json_decode('{
            "schueler": [
                {
                    "id": 1,
                    "jahrgangID": 1,
                    "klasseID": 2,
                    "nachname": "Huiß",
                    "vorname": "Vanessa",
                    "geschlecht": "w",
                    "bilingualeSprache": null,
                    "istZieldifferent": false,
                    "istDaZFoerderung": false
                }
            ]
        }', true);

        new DataImportService($data);
        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not create schueler with missing "jahrgangID"
     *
     * @return void
     */
    public function test_it_does_not_create_schueler_with_missing_jahrgangId(): void
    {
        $jahrgang = Jahrgang::factory()->create();
        Klasse::factory()->create(['idJahrgang' => $jahrgang->id]);

        $data = json_decode('{
            "schueler": [
                {
                    "id": 1,
                    "klasseID": 1,
                    "nachname": "Huiß",
                    "vorname": "Vanessa",
                    "geschlecht": "w",
                    "bilingualeSprache": null,
                    "istZieldifferent": false,
                    "istDaZFoerderung": false
                }
            ]
        }', true);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not create schueler with empty "jahrgangID"
     *
     * @return void
     */
    public function test_it_does_not_create_schueler_with_empty_jahrgangId(): void
    {
        $jahrgang = Jahrgang::factory()->create();
        Klasse::factory()->create(['idJahrgang' => $jahrgang->id]);

        $data = json_decode('{
            "schueler": [
                {
                    "id": 1,
                    "jahrgangID": null,
                    "klasseID": 1,
                    "nachname": "Huiß",
                    "vorname": "Vanessa",
                    "geschlecht": "w",
                    "bilingualeSprache": null,
                    "istZieldifferent": false,
                    "istDaZFoerderung": false
                }
            ]
        }', true);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not create schueler with negative "jahrgangId"
     *
     * @return void
     */
    public function test_it_does_not_create_schueler_with_negative_jahrgangId(): void
    {
        $jahrgang = Jahrgang::factory()->create();
        Klasse::factory()->create(['idJahrgang' => $jahrgang->id]);

        $data = json_decode('{
            "schueler": [
                {
                    "id": 1,
                    "jahrgangID": -1,
                    "klasseID": 1,
                    "nachname": "Huiß",
                    "vorname": "Vanessa",
                    "geschlecht": "w",
                    "bilingualeSprache": null,
                    "istZieldifferent": false,
                    "istDaZFoerderung": false
                }
            ]
        }', true);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not create schueler with non integer "jahrgangID"
     *
     * @return void
     */
    public function test_it_does_not_create_schueler_with_non_integer_jahrgangId(): void
    {
        $jahrgang = Jahrgang::factory()->create();
        Klasse::factory()->create(['idJahrgang' => $jahrgang->id]);

        $data = json_decode('{
            "schueler": [
                {
                    "id": 1,
                    "jahrgangID": "X",
                    "klasseID": 1,
                    "nachname": "Huiß",
                    "vorname": "Vanessa",
                    "geschlecht": "w",
                    "bilingualeSprache": null,
                    "istZieldifferent": false,
                    "istDaZFoerderung": false
                }
            ]
        }', true);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not create schueler with non existing related "jahrgang"
     *
     * @return void
     */
    public function test_it_does_not_create_schueler_with_non_existing_related_jahrgang(): void
    {
        $jahrgang = Jahrgang::factory()->create();
        Klasse::factory()->create(['idJahrgang' => $jahrgang->id]);

        $data = json_decode('{
            "schueler": [
                {
                    "id": 1,
                    "jahrgangID": 2,
                    "klasseID": 1,
                    "nachname": "Huiß",
                    "vorname": "Vanessa",
                    "geschlecht": "w",
                    "bilingualeSprache": null,
                    "istZieldifferent": false,
                    "istDaZFoerderung": false
                }
            ]
        }', true);

        new DataImportService($data);
        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not create schueler with missing "geschlecht"
     *
     * @return void
     */
    public function test_it_does_not_create_schueler_with_missing_geschleht(): void
    {
        $jahrgang = Jahrgang::factory()->create();
        Klasse::factory()->create(['idJahrgang' => $jahrgang->id]);

        $data = json_decode('{
            "schueler": [
                {
                    "id": 1,
                    "klasseID": 1,
                    "jahrgangID": 1,
                    "nachname": "Huiß",
                    "vorname": "Vanessa",
                    "bilingualeSprache": null,
                    "istZieldifferent": false,
                    "istDaZFoerderung": false
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not create schueler with empty "geschlecht"
     *
     * @return void
     */
    public function test_it_does_not_create_schueler_with_empty_geschleht(): void
    {
        $jahrgang = Jahrgang::factory()->create();
        Klasse::factory()->create(['idJahrgang' => $jahrgang->id]);

        $data = json_decode('{
            "schueler": [
                {
                    "id": 1,
                    "klasseID": 1,
                    "jahrgangID": 1,
                    "nachname": "Huiß",
                    "vorname": "Vanessa",
                    "geschlecht": null,
                    "bilingualeSprache": null,
                    "istZieldifferent": false,
                    "istDaZFoerderung": false
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It creates schuler with proper "geschlecht" if incorrect provided
     *
     * @return void
     */
    public function test_it_creates_schueler_with_proper_geschlecht_if_incorrect_provided(): void
    {
        $jahrgang = Jahrgang::factory()->create();
        Klasse::factory()->create(['idJahrgang' => $jahrgang->id]);

        $data = json_decode('{
            "schueler": [
                {
                    "id": 1,
                    "klasseID": 1,
                    "jahrgangID": 1,
                    "nachname": "Huiß",
                    "vorname": "Vanessa",
                    "geschlecht": "R",
                    "bilingualeSprache": null,
                    "istZieldifferent": false,
                    "istDaZFoerderung": false
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, [
                'id' => 1,
                'geschlecht' => 'x',
            ]);
    }
}
