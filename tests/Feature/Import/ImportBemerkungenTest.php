<?php

namespace Tests\Feature\Import;

use App\Models\{Bemerkung, Jahrgang, Klasse};
use App\Services\DataImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImportBemerkungenTest extends TestCase
{
    use RefreshDatabase;

    public const TABLE = 'bemerkungen';

    /**
     * It creates "Bemerkungen"
     *
     * @return void
     */
    public function test_it_creates_bemerkungen(): void
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
                    "bemerkungen": {
                        "ASV": null,
                        "tsASV": "2023-03-28 07:00:19.031",
                        "AUE": null,
                        "tsAUE": "2023-03-28 07:00:19.031",
                        "ZB": null,
                        "tsZB": "2023-03-28 07:00:19.031",
                        "LELS": null,
                        "schulformEmpf": null,
                        "individuelleVersetzungsbemerkungen": null,
                        "tsIndividuelleVersetzungsbemerkungen": "2023-03-28 07:00:19.031",
                        "foerderbemerkungen": null
                    },
                    "zp10": null,
                    "bkabschluss": null
                }
            ]
        }', true);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, [
                'schueler_id' => 1
            ]);
    }

    /**
     * It creates "Bemerkungen"
     *
     * @return void
     */
    public function test_it_updates_bemerkungen_when_timestamp_is_latter(): void
    {
        $jahrgang = Jahrgang::factory()->create();
        Klasse::factory()->create(['idJahrgang' => $jahrgang->id]);

        Bemerkung::factory()->create([
            'schueler_id' => 1,
            'ASV' => 'lorem old',
            'tsASV' => '2000-01-02 00:00:00.000',
            'AUE' => 'ipsum old',
            'tsAUE' => '2000-01-02 00:00:00.000',
            'ZB' => 'dolor old',
            'tsZB' => '2000-01-02 00:00:00.000',
            'individuelleVersetzungsbemerkungen' => 'amet old',
            'tsIndividuelleVersetzungsbemerkungen' => '2000-01-02 00:00:00.000',
        ]);

        $data = json_decode('{
            "schueler": [
                {
                    "id": 1,
                    "jahrgangID": 1,
                    "klasseID": 1,
                    "nachname": "Huiß",
                    "vorname": "Vanessa",
                    "geschlecht": "w",
                    "bemerkungen": {
                        "ASV": "lorem new",
                        "tsASV": "2000-01-01 00:00:00.000",
                        "AUE": "ipsum new",
                        "tsAUE": "2000-01-01 00:00:00.000",
                        "ZB": "dolor new",
                        "tsZB": "2000-01-03 00:00:00.000",
                        "LELS": null,
                        "schulformEmpf": null,
                        "individuelleVersetzungsbemerkungen": "amet new",
                        "tsIndividuelleVersetzungsbemerkungen": "2000-01-03 00:00:00.000",
                        "foerderbemerkungen": null
                    },
                    "zp10": null,
                    "bkabschluss": null
                }
            ]
        }', true);

        new DataImportService($data);

        $format = 'Y-m-d H:i:s.v';
        $bemerkung = Bemerkung::first();

        $unchangedTimestamp = '2000-01-02 00:00:00.000';
        $changedTimestamp = '2000-01-03 00:00:00.000';

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, [
                'schueler_id' => 1,
                'ASV' => 'lorem old',
                'AUE' => 'ipsum old',
                'ZB' => 'dolor new',
                'individuelleVersetzungsbemerkungen' => 'amet new',
            ]);

        $this->assertEquals($unchangedTimestamp, $bemerkung->tsASV->format($format));
        $this->assertEquals($unchangedTimestamp, $bemerkung->tsAUE->format($format));
        $this->assertEquals($changedTimestamp, $bemerkung->tsZB->format($format));
        $this->assertEquals($changedTimestamp, $bemerkung->tsIndividuelleVersetzungsbemerkungen->format($format));
    }

    /**
     * It does not update "Bemerkungen" if ASV Timestamp is not present
     *
     * @return void
     */
    public function test_it_does_not_update_bemerkungen_if_asv_ts_is_no_present(): void
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
                    "bemerkungen": {
                        "ASV": null,
                        "AUE": null,
                        "tsAUE": "2023-03-28 07:00:19.031",
                        "ZB": null,
                        "tsZB": "2023-03-28 07:00:19.031",
                        "LELS": null,
                        "schulformEmpf": null,
                        "individuelleVersetzungsbemerkungen": null,
                        "tsIndividuelleVersetzungsbemerkungen": "2023-03-28 07:00:19.031",
                        "foerderbemerkungen": null
                    }
                }
            ]
        }', true);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not update "Bemerkungen" if ASV Timestamp is empty
     *
     * @return void
     */
    public function test_it_does_not_update_bemerkungen_if_asv_ts_is_empty(): void
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
                    "bemerkungen": {
                        "ASV": null,
                        "tsASV": null,
                        "AUE": null,
                        "tsAUE": "2023-03-28 07:00:19.031",
                        "ZB": null,
                        "tsZB": "2023-03-28 07:00:19.031",
                        "LELS": null,
                        "schulformEmpf": null,
                        "individuelleVersetzungsbemerkungen": null,
                        "tsIndividuelleVersetzungsbemerkungen": "2023-03-28 07:00:19.031",
                        "foerderbemerkungen": null
                    }
                }
            ]
        }', true);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not update "Bemerkungen" if AUE Timestamp is not present
     *
     * @return void
     */
    public function test_it_does_not_update_bemerkungen_if_aue_ts_is_no_present(): void
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
                    "bemerkungen": {
                        "ASV": null,
                        "tsASV": "2023-03-28 07:00:19.031",
                        "AUE": null,
                        "ZB": null,
                        "tsZB": "2023-03-28 07:00:19.031",
                        "LELS": null,
                        "schulformEmpf": null,
                        "individuelleVersetzungsbemerkungen": null,
                        "tsIndividuelleVersetzungsbemerkungen": "2023-03-28 07:00:19.031",
                        "foerderbemerkungen": null
                    }
                }
            ]
        }', true);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not update "Bemerkungen" if AUE Timestamp is empty
     *
     * @return void
     */
    public function test_it_does_not_update_bemerkungen_if_aue_ts_is_empty(): void
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
                    "bemerkungen": {
                        "ASV": null,
                        "tsASV": "2023-03-28 07:00:19.031",
                        "AUE": null,
                        "tsAUE": null,
                        "ZB": null,
                        "tsZB": "2023-03-28 07:00:19.031",
                        "LELS": null,
                        "schulformEmpf": null,
                        "individuelleVersetzungsbemerkungen": null,
                        "tsIndividuelleVersetzungsbemerkungen": "2023-03-28 07:00:19.031",
                        "foerderbemerkungen": null
                    }
                }
            ]
        }', true);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not update "Bemerkungen" if ZB Timestamp is not present
     *
     * @return void
     */
    public function test_it_does_not_update_bemerkungen_if_zb_ts_is_no_present(): void
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
                    "bemerkungen": {
                        "ASV": null,
                        "tsASV": "2023-03-28 07:00:19.031",
                        "AUE": null,
                        "tsAUE": "2023-03-28 07:00:19.031",
                        "ZB": null,
                        "LELS": null,
                        "schulformEmpf": null,
                        "individuelleVersetzungsbemerkungen": null,
                        "tsIndividuelleVersetzungsbemerkungen": "2023-03-28 07:00:19.031",
                        "foerderbemerkungen": null
                    }
                }
            ]
        }', true);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not update "Bemerkungen" if ZB Timestamp is empty
     *
     * @return void
     */
    public function test_it_does_not_update_bemerkungen_if_zb_ts_is_empty(): void
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
                    "bemerkungen": {
                        "ASV": null,
                        "tsASV": "2023-03-28 07:00:19.031",
                        "AUE": null,
                        "tsAUE": "2023-03-28 07:00:19.031",
                        "ZB": null,
                        "tsZB": null,
                        "LELS": null,
                        "schulformEmpf": null,
                        "individuelleVersetzungsbemerkungen": null,
                        "tsIndividuelleVersetzungsbemerkungen": "2023-03-28 07:00:19.031",
                        "foerderbemerkungen": null
                    }
                }
            ]
        }', true);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not update "Bemerkungen" if individuelleVersetzungsbemerkungen Timestamp is not present
     *
     * @return void
     */
    public function test_it_does_not_update_bemerkungen_if_individuelleVersetzungsbemerkungen_ts_is_no_present(): void
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
                    "bemerkungen": {
                        "ASV": null,
                        "tsASV": "2023-03-28 07:00:19.031",
                        "AUE": null,
                        "tsAUE": "2023-03-28 07:00:19.031",
                        "ZB": null,
                        "tsZB": "2023-03-28 07:00:19.031",
                        "LELS": null,
                        "schulformEmpf": null,
                        "individuelleVersetzungsbemerkungen": null,
                        "foerderbemerkungen": null
                    }
                }
            ]
        }', true);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not update "Bemerkungen" if individuelleVersetzungsbemerkungen Timestamp is empty
     *
     * @return void
     */
    public function test_it_does_not_update_bemerkungen_if_individuelleVersetzungsbemerkungen_ts_is_empty(): void
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
                    "bemerkungen": {
                        "ASV": null,
                        "tsASV": "2023-03-28 07:00:19.031",
                        "AUE": null,
                        "tsAUE": "2023-03-28 07:00:19.031",
                        "ZB": null,
                        "tsZB": "2023-03-28 07:00:19.031",
                        "LELS": null,
                        "schulformEmpf": null,
                        "individuelleVersetzungsbemerkungen": null,
                        "tsIndividuelleVersetzungsbemerkungen": null,
                        "foerderbemerkungen": null
                    }
                }
            ]
        }', true);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not update "Bemerkungen" if ASV is not present
     *
     * @return void
     */
    public function test_it_does_not_update_bemerkungen_if_asv_is_no_present(): void
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
                    "bemerkungen": {
                        "tsASV": "2023-03-28 07:00:19.031",
                        "AUE": null,
                        "tsAUE": "2023-03-28 07:00:19.031",
                        "ZB": null,
                        "tsZB": "2023-03-28 07:00:19.031",
                        "LELS": null,
                        "schulformEmpf": null,
                        "individuelleVersetzungsbemerkungen": null,
                        "foerderbemerkungen": null
                    }
                }
            ]
        }', true);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not update "Bemerkungen" if AUE is not present
     *
     * @return void
     */
    public function test_it_does_not_update_bemerkungen_if_aue_is_no_present(): void
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
                    "bemerkungen": {
                        "ASV": null,
                        "tsASV": "2023-03-28 07:00:19.031",
                        "tsAUE": "2023-03-28 07:00:19.031",
                        "ZB": null,
                        "tsZB": "2023-03-28 07:00:19.031",
                        "LELS": null,
                        "schulformEmpf": null,
                        "individuelleVersetzungsbemerkungen": null,
                        "foerderbemerkungen": null
                    }
                }
            ]
        }', true);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not update "Bemerkungen" if ZB is not present
     *
     * @return void
     */
    public function test_it_does_not_update_bemerkungen_if_zb_is_no_present(): void
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
                    "bemerkungen": {
                        "ASV": null,
                        "tsASV": "2023-03-28 07:00:19.031",
                        "AUE": null,
                        "tsAUE": "2023-03-28 07:00:19.031",
                        "tsZB": "2023-03-28 07:00:19.031",
                        "LELS": null,
                        "schulformEmpf": null,
                        "individuelleVersetzungsbemerkungen": null,
                        "foerderbemerkungen": null
                    }
                }
            ]
        }', true);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not update "Bemerkungen" if individuelleVersetzungsbemerkungen is not present
     *
     * @return void
     */
    public function test_it_does_not_update_bemerkungen_if_individuelleVersetzungsbemerkungen_is_no_present(): void
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
                    "bemerkungen": {
                        "ASV": null,
                        "tsASV": "2023-03-28 07:00:19.031",
                        "AUE": null,
                        "tsAUE": "2023-03-28 07:00:19.031",
                        "ZB": null,
                        "tsZB": "2023-03-28 07:00:19.031",
                        "LELS": null,
                        "schulformEmpf": null,
                        "foerderbemerkungen": null
                    }
                }
            ]
        }', true);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 0);
    }
}
