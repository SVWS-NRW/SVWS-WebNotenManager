<?php

namespace Tests\Feature\Import;

use App\Models\Jahrgang;
use App\Services\DataImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImportJahrgaengeTest extends TestCase
{
    use RefreshDatabase;

    public const TABLE = 'jahrgaenge';

    /**
     * Get data
     *
     * @return array
     */
    private function getData(): array
    {
        return json_decode('{
            "jahrgaenge": [
                {
                    "id": 1,
                    "kuerzel": "Q2",
                    "kuerzelAnzeige": "Q2",
                    "beschreibung": "Qualifikationsphase 2",
                    "stufe": "SII-3",
                    "sortierung": 12
                }
            ]
        }', true);
    }

    /** It creates */
    public function test_it_creates(): void
    {
        $data = $this->getData();
        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, [
                'id' => 1,
                'kuerzel' => 'Q2',
                'kuerzelAnzeige' => 'Q2',
                'beschreibung' => 'Qualifikationsphase 2',
                'stufe' => 'SII-3',
                'sortierung' => 12
            ]);
    }

    /** It does not create with "ID" missing */
    public function test_it_does_not_create_with_id_missing(): void
    {
        $data = $this->getData();
        unset($data['jahrgaenge'][0]['id']);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with null "ID" */
    public function test_it_does_not_create_with_null_id(): void
    {
        $data = $this->getData();
        $data['jahrgaenge'][0]['id'] = null;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with empty "ID" */
    public function test_it_does_not_create_with_empty_id(): void
    {
        $data = $this->getData();
        $data['jahrgaenge'][0]['id'] = '';

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with negative "ID" */
    public function test_it_does_not_create_with_negative_id(): void
    {
        $data = $this->getData();
        $data['jahrgaenge'][0]['id'] = -1;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with non-numeric "ID" */
    public function test_it_does_not_create_with_non_numeric_id(): void
    {
        $data = $this->getData();
        $data['jahrgaenge'][0]['id'] = 'X';

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with existing "ID" */
    public function test_it_does_not_create_with_existing_id(): void
    {
        Jahrgang::factory()->create([
            'id' => 1,
            'kuerzel' => 'Q2',
            'kuerzelAnzeige' => 'Q2',
            'beschreibung' => 'Qualifikationsphase 2',
            'stufe' => 'SII-3',
            'sortierung' => 12
        ]);

        $data = $this->getData();
        $data['jahrgaenge'][0]['id'] = 1;
        $data['jahrgaenge'][0]['kuerzel'] = 'Q3';

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, ['id' => 1, 'kuerzel' => 'Q2'])
            ->assertDatabaseMissing(self::TABLE, ['id' => 1, 'kuerzel' => 'Q3']);
    }

    /** It does not create with missing "Kuerzel" */
    public function test_it_does_not_create_with_missing_kuerzel(): void
    {
        $data = $this->getData();
        unset($data['jahrgaenge'][0]['kuerzel']);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with null kuerzel */
    public function test_it_does_not_create_with_null_kuerzel(): void
    {
        $data = $this->getData();
        $data['jahrgaenge'][0]['kuerzel'] = null;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with empty "Kuerzel" */
    public function test_it_does_not_create_with_empty_kuerzel(): void
    {
        $data = $this->getData();
        $data['jahrgaenge'][0]['kuerzel'] = '';

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with existing "Kuerzel" */
    public function test_it_does_not_create_with_existing_kuerzel(): void
    {
        Jahrgang::factory()->create([
            'kuerzel' => 'Q2',
            'kuerzelAnzeige' => 'Q2',
        ]);

        $data = $this->getData();
        $data['jahrgaenge'][0]['kuerzel'] = 'Q2';
        $data['jahrgaenge'][0]['kuerzelAnzeige'] = 'Q3';

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, [
                'kuerzel' => 'Q2',
                'kuerzelAnzeige' => 'Q2',
            ])->assertDatabaseMissing(self::TABLE, [
                'kuerzel' => 'Q2',
                'kuerzelAnzeige' => 'Q3',
            ]);
    }

    /** It does not create with "KuerzelAnzeige" missing */
    public function test_it_does_not_create_with_kuerzel_anzeige_missing(): void
    {
        $data = $this->getData();
        unset($data['jahrgaenge'][0]['kuerzelAnzeige']);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with null "KuerzelAnzeige" */
    public function test_it_does_not_create_with_null_kuerzel_anzeige(): void
    {
        $data = $this->getData();
        $data['jahrgaenge'][0]['kuerzelAnzeige'] = null;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with empty "KuerzelAnzeige" */
    public function test_it_does_not_create_with_empty_kuerzel_anzeige(): void
    {
        $data = $this->getData();
        $data['jahrgaenge'][0]['kuerzelAnzeige'] = '';

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with "Beschreibung" missing */
    public function test_it_does_not_create_with_beschreibung_missing(): void
    {
        $data = $this->getData();
        unset($data['jahrgaenge'][0]['beschreibung']);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with NULL "Beschreibung" */
    public function test_it_does_not_create_with_null_beschreibung(): void
    {
        $data = $this->getData();
        $data['jahrgaenge'][0]['beschreibung'] = null;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with empty "Beschreibung" */
    public function test_it_does_not_create_with_empty_beschreibung(): void
    {
        $data = $this->getData();
        $data['jahrgaenge'][0]['beschreibung'] = '';

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with "Stufe" missing */
    public function test_it_does_not_create_with_stufe_missing(): void
    {
        $data = $this->getData();
        unset($data['jahrgaenge'][0]['stufe']);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with NULL "Stufe" */
    public function test_it_does_not_create_with_null_stufe(): void
    {
        $data = $this->getData();
        $data['jahrgaenge'][0]['stufe'] = null;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with empty "Stufe" */
    public function test_it_does_not_create_with_empty_stufe(): void
    {
        $data = $this->getData();
        $data['jahrgaenge'][0]['stufe'] = '';

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with sortierung missing */
    public function test_it_does_not_create_with_sortierung_missing(): void
    {
        $data = $this->getData();
        unset($data['jahrgaenge'][0]['sortierung']);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with NULL "Sortierung" */
    public function test_it_does_not_create_with_null_sortierung(): void
    {
        $data = $this->getData();
        $data['jahrgaenge'][0]['sortierung'] = null;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with empty "Sortierung" */
    public function test_it_does_not_create_with_empty_sortierung(): void
    {
        $data = $this->getData();
        $data['jahrgaenge'][0]['sortierung'] = '';

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with existing "Sortierung" */
    public function test_it_does_not_create_with_existing_sortierung(): void
    {
        Jahrgang::factory()->create([
            'id' => 1,
            'sortierung' => 12,
        ]);

        $data = $this->getData();
        $data['jahrgaenge'][0]['sortierung'] = 12;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseMissing(self::TABLE, [
                'id' => 2,
                'sortierung' => 12
            ]);
    }

    /** It returns when the "Jahrgaenge" array is empty */
    public function test_it_returns_when_the_jahrgaenge_array_is_missing(): void
    {
        $data = json_decode('{}', true);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It returns when the "Jahrgaenge" array is empty */
    public function test_it_returns_when_the_jahrgaenge_array_is_empty(): void
    {
        $data = json_decode('{
            "jahrgaenge": []
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }
}
