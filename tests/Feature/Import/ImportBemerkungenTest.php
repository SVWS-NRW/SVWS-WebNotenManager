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
     * Get data
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
                    "bemerkungen": {
                        "ASV": null,
                        "tsASV": "2000-01-03 00:00:00.000",
                        "AUE": null,
                        "tsAUE": "2000-01-03 00:00:00.000",
                        "ZB": null,
                        "tsZB": "2000-01-03 00:00:00.000",
                        "LELS": null,
                        "schulformEmpf": null,
                        "individuelleVersetzungsbemerkungen": null,
                        "tsIndividuelleVersetzungsbemerkungen": "2000-01-03 00:00:00.000",
                        "foerderbemerkungen": null
                    },
                    "zp10": null,
                    "bkabschluss": null,
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

    /** It creates */
    public function test_it_creates(): void
    {
        $jahrgang = Jahrgang::factory()->create();
        Klasse::factory()->create(['idJahrgang' => $jahrgang->id]);

        (new DataImportService($this->getData()))->execute();

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, ['schueler_id' => 1]);
    }

    /** It updates when timestamp is latter */
    public function test_it_updates_when_timestamp_is_latter(): void
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

        (new DataImportService($this->getData()))->execute();

        $format = 'Y-m-d H:i:s.v';
        $bemerkung = Bemerkung::first();

        $changedTimestamp = '2000-01-03 00:00:00.000';

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, [
                'schueler_id' => 1,
                'ASV' => null,
                'AUE' => null,
                'ZB' => null,
                'individuelleVersetzungsbemerkungen' => null,
            ]);

        $this->assertEquals($changedTimestamp, $bemerkung->tsASV->format($format));
        $this->assertEquals($changedTimestamp, $bemerkung->tsAUE->format($format));
        $this->assertEquals($changedTimestamp, $bemerkung->tsZB->format($format));
        $this->assertEquals($changedTimestamp, $bemerkung->tsIndividuelleVersetzungsbemerkungen->format($format));
    }

    /** It does not update if ASV Timestamp is not present */
    public function test_it_does_not_update_if_asv_ts_is_no_present(): void
    {
        $jahrgang = Jahrgang::factory()->create();
        Klasse::factory()->create(['idJahrgang' => $jahrgang->id]);

        $data = $this->getData();
        unset($data['schueler'][0]['bemerkungen']['tsASV']);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not update if ASV Timestamp is null */
    public function test_it_does_not_update_if_asv_ts_is_null(): void
    {
        $jahrgang = Jahrgang::factory()->create();
        Klasse::factory()->create(['idJahrgang' => $jahrgang->id]);

        $data = $this->getData();
        $data['schueler'][0]['bemerkungen']['tsASV'] = null;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not update if ASV Timestamp is empty */
    public function test_it_does_not_update_if_asv_ts_is_empty(): void
    {
        $jahrgang = Jahrgang::factory()->create();
        Klasse::factory()->create(['idJahrgang' => $jahrgang->id]);

        $data = $this->getData();
        $data['schueler'][0]['bemerkungen']['tsASV'] = '';

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not update if AUE Timestamp is not present */
    public function test_it_does_not_update_if_aue_ts_is_no_present(): void
    {
        $jahrgang = Jahrgang::factory()->create();
        Klasse::factory()->create(['idJahrgang' => $jahrgang->id]);

        $data = $this->getData();
        unset($data['schueler'][0]['bemerkungen']['tsAUE']);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not update if AUE Timestamp is null */
    public function test_it_does_not_update_if_aue_ts_is_null(): void
    {
        $jahrgang = Jahrgang::factory()->create();
        Klasse::factory()->create(['idJahrgang' => $jahrgang->id]);

        $data = $this->getData();
        $data['schueler'][0]['bemerkungen']['tsAUE'] = null;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not update if AUE Timestamp is empty */
    public function test_it_does_not_update_if_aue_ts_is_empty(): void
    {
        $jahrgang = Jahrgang::factory()->create();
        Klasse::factory()->create(['idJahrgang' => $jahrgang->id]);

        $data = $this->getData();
        $data['schueler'][0]['bemerkungen']['tsAUE'] = '';

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not update if ZB Timestamp is not present */
    public function test_it_does_not_update_if_zb_ts_is_no_present(): void
    {
        $jahrgang = Jahrgang::factory()->create();
        Klasse::factory()->create(['idJahrgang' => $jahrgang->id]);

        $data = $this->getData();
        unset($data['schueler'][0]['bemerkungen']['tsZB']);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not update if ZB Timestamp is null */
    public function test_it_does_not_update_if_zb_ts_is_null(): void
    {
        $jahrgang = Jahrgang::factory()->create();
        Klasse::factory()->create(['idJahrgang' => $jahrgang->id]);

        $data = $this->getData();
        $data['schueler'][0]['bemerkungen']['tsZB'] = null;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not update if ZB Timestamp is empty */
    public function test_it_does_not_update_if_zb_ts_is_empty(): void
    {
        $jahrgang = Jahrgang::factory()->create();
        Klasse::factory()->create(['idJahrgang' => $jahrgang->id]);

        $data = $this->getData();
        $data['schueler'][0]['bemerkungen']['tsZB'] = '';

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not update if tsIndividuelleVersetzungsbemerkungen is not present */
    public function test_it_does_not_update_if_tsIndividuelleVersetzungsbemerkungen_is_no_present(): void
    {
        $jahrgang = Jahrgang::factory()->create();
        Klasse::factory()->create(['idJahrgang' => $jahrgang->id]);

        $data = $this->getData();
        unset($data['schueler'][0]['bemerkungen']['tsIndividuelleVersetzungsbemerkungen']);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not update if tsIndividuelleVersetzungsbemerkungen is null */
    public function test_it_does_not_update_if_tsIndividuelleVersetzungsbemerkungen_is_null(): void
    {
        $jahrgang = Jahrgang::factory()->create();
        Klasse::factory()->create(['idJahrgang' => $jahrgang->id]);

        $data = $this->getData();
        $data['schueler'][0]['bemerkungen']['tsIndividuelleVersetzungsbemerkungen'] = null;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not update if tsIndividuelleVersetzungsbemerkungen is empty */
    public function test_it_does_not_update_if_tsIndividuelleVersetzungsbemerkungen_is_empty(): void
    {
        $jahrgang = Jahrgang::factory()->create();
        Klasse::factory()->create(['idJahrgang' => $jahrgang->id]);

        $data = $this->getData();
        $data['schueler'][0]['bemerkungen']['tsIndividuelleVersetzungsbemerkungen'] = '';

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not update if ASV is not present */
    public function test_it_does_not_update_if_asv_is_no_present(): void
    {
        $jahrgang = Jahrgang::factory()->create();
        Klasse::factory()->create(['idJahrgang' => $jahrgang->id]);

        $data = $this->getData();
        unset($data['schueler'][0]['bemerkungen']['ASV']);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It updates if ASV is null */
    public function test_it_updates_if_asv_is_null(): void
    {
        $jahrgang = Jahrgang::factory()->create();
        Klasse::factory()->create(['idJahrgang' => $jahrgang->id]);

        $data = $this->getData();
        $data['schueler'][0]['bemerkungen']['ASV'] = null;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1);
    }

    /** It updates if ASV is empty */
    public function test_it_updates_if_asv_is_empty(): void
    {
        $jahrgang = Jahrgang::factory()->create();
        Klasse::factory()->create(['idJahrgang' => $jahrgang->id]);

        $data = $this->getData();
        $data['schueler'][0]['bemerkungen']['ASV'] = '';

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1);
    }

    /** It does not update if AUE is not present */
    public function test_it_does_not_update_if_aue_is_no_present(): void
    {
        $jahrgang = Jahrgang::factory()->create();
        Klasse::factory()->create(['idJahrgang' => $jahrgang->id]);

        $data = $this->getData();
        unset($data['schueler'][0]['bemerkungen']['AUE']);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It updates if AUE is null */
    public function test_it_updates_if_aue_is_null(): void
    {
        $jahrgang = Jahrgang::factory()->create();
        Klasse::factory()->create(['idJahrgang' => $jahrgang->id]);

        $data = $this->getData();
        $data['schueler'][0]['bemerkungen']['AUE'] = null;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1);
    }

    /** It updates if AUE is empty */
    public function test_it_updates_if_aue_is_empty(): void
    {
        $jahrgang = Jahrgang::factory()->create();
        Klasse::factory()->create(['idJahrgang' => $jahrgang->id]);

        $data = $this->getData();
        $data['schueler'][0]['bemerkungen']['AUE'] = '';

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1);
    }

    /** It does not update if ZB is not present */
    public function test_it_does_not_update_if_zb_is_no_present(): void
    {
        $jahrgang = Jahrgang::factory()->create();
        Klasse::factory()->create(['idJahrgang' => $jahrgang->id]);

        $data = $this->getData();
        unset($data['schueler'][0]['bemerkungen']['ZB']);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It updates if ZB is null */
    public function test_it_updates_if_zb_is_null(): void
    {
        $jahrgang = Jahrgang::factory()->create();
        Klasse::factory()->create(['idJahrgang' => $jahrgang->id]);

        $data = $this->getData();
        $data['schueler'][0]['bemerkungen']['ZB'] = null;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1);
    }

    /** It updates if ZB is empty */
    public function test_it_updates_if_zb_is_empty(): void
    {
        $jahrgang = Jahrgang::factory()->create();
        Klasse::factory()->create(['idJahrgang' => $jahrgang->id]);

        $data = $this->getData();
        $data['schueler'][0]['bemerkungen']['ZB'] = '';

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1);
    }

    /** It does not update if IndividuelleVersetzungsbemerkungen is not present */
    public function test_it_does_not_update_if_individuelleVersetzungsbemerkungen_is_no_present(): void
    {
        $jahrgang = Jahrgang::factory()->create();
        Klasse::factory()->create(['idJahrgang' => $jahrgang->id]);

        $data = $this->getData();
        unset($data['schueler'][0]['bemerkungen']['individuelleVersetzungsbemerkungen']);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It updates if individuelleVersetzungsbemerkungen is null */
    public function test_it_updates_if_individuelleVersetzungsbemerkungen_is_null(): void
    {
        $jahrgang = Jahrgang::factory()->create();
        Klasse::factory()->create(['idJahrgang' => $jahrgang->id]);

        $data = $this->getData();
        $data['schueler'][0]['bemerkungen']['individuelleVersetzungsbemerkungen'] = null;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1);
    }

    /** It updates if individuelleVersetzungsbemerkungen is empty */
    public function test_it_updates_if_individuelleVersetzungsbemerkungen_is_empty(): void
    {
        $jahrgang = Jahrgang::factory()->create();
        Klasse::factory()->create(['idJahrgang' => $jahrgang->id]);

        $data = $this->getData();
        $data['schueler'][0]['bemerkungen']['individuelleVersetzungsbemerkungen'] = '';

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1);
    }

    /** It does not update core data */
    public function test_it_does_not_update_core_data(): void
    {
        $jahrgang = Jahrgang::factory()->create();
        Klasse::factory()->create(['idJahrgang' => $jahrgang->id]);

        Bemerkung::factory()->create([
            'schueler_id' => 1,
            'LELS' => 'old',
            'schulformEmpf' => 'old',
            'foerderbemerkungen' => 'old',
        ]);

        $data = $this->getData();
        $data['schueler'][0]['bemerkungen']['LELS'] = 'new';
        $data['schueler'][0]['bemerkungen']['schulformEmpf'] = 'new';
        $data['schueler'][0]['bemerkungen']['foerderbemerkungen'] = 'new';

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, [
                'schueler_id' => 1,
                'LELS' => 'old',
                'schulformEmpf' => 'old',
                'foerderbemerkungen' => 'old',
            ]);
    }
}
