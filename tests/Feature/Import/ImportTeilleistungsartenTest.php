<?php

namespace Tests\Feature\Import;

use App\Models\Teilleistungsart;
use App\Services\DataImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImportTeilleistungsartenTest extends TestCase
{
    use RefreshDatabase;

    public const TABLE = 'teilleistungsarten';

    /**
     * Import data
     *
     * @return array
     */
    private function data(): array
    {
        return json_decode('{
            "teilleistungsarten": [
                {
                    "id": 1,
                    "bezeichnung": "Klassenarbeit/Klausur",
                    "sortierung": 1,
                    "gewichtung": 1
                }
            ]
        }', true);
    }

    /** It creates "Teilleistungsarten" */
    public function test_it_creates_teilleistungsarten(): void
    {
        (new DataImportService($this->data()))->execute();

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, [
                'id' => 1,
                'bezeichnung' => 'Klassenarbeit/Klausur',
                'sortierung' => 1,
                'gewichtung' => 1,
            ]);
    }

    /** It does not update "Teilleistungsarten"  */
    public function test_it_does_not_update_teilleistungen(): void
    {
        Teilleistungsart::factory()->create([
            'id' => 1,
            'bezeichnung' => 'Old value',
            'sortierung' => 1,
            'gewichtung' => 1,
        ]);

        $data = $this->data();
        $data['teilleistungsarten'][0]['bezeichnung'] = 'New value';
        $data['teilleistungsarten'][0]['sortierung'] = 2;
        $data['teilleistungsarten'][0]['gewichtung'] = 2;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, [
                'id' => 1,
                'bezeichnung' => 'Old value',
                'sortierung' => 1,
                'gewichtung' => 1,
            ])
            ->assertDatabaseMissing(self::TABLE, [
                'id' => 1,
                'bezeichnung' => 'New value',
                'sortierung' => 2,
                'gewichtung' => 2,
            ]);
    }

    /** It does not create "Teilleistungsarten" with missing "Bezeichnung" */
    public function test_it_does_not_create_teilleisungsarten_with_missing_bezeichnung(): void
    {
        $data = $this->data();
        unset($data['teilleistungsarten'][0]['bezeichnung']);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create "Teilleistungsarten" with empty "Bezeichnung" */
    public function test_it_does_not_create_teilleisungsarten_with_empty_bezeichnung(): void
    {
        $data = $this->data();
        $data['teilleistungsarten'][0]['bezeichnung'] = null;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create "Teilleistungsarten" with already existsing "Bezeichnung" */
    public function test_it_does_not_create_teilleisungsarten_with_existing_bezeichnung(): void
    {
        Teilleistungsart::factory()->create(['bezeichnung' => 'Klassenarbeit/Klausur']);

        $data = $this->data();
        $data['teilleistungsarten'][0]['bezeichnung'] = 'Klassenarbeit/Klausur';

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1);
    }

    /** It does not create "Teilleistungsarten" with missing "id" */
    public function test_it_does_not_create_teilleisungsarten_with_missing_id(): void
    {
        $data = $this->data();
        unset($data['teilleistungsarten'][0]['id']);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create "Teilleistungsarten" with empty "id" */
    public function test_it_does_not_create_teilleisungsarten_with_empty_id(): void
    {
        $data = $this->data();
        $data['teilleistungsarten'][0]['id'] = null;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create "Teilleistungsarten" with non-numeric "id" */
    public function test_it_does_not_create_teilleisungsarten_with_non_numeric_id(): void
    {
        $data = $this->data();
        $data['teilleistungsarten'][0]['id'] = 'x';

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create "Teilleistungsarten" with missing "Gewichtung" */
    public function test_it_does_not_create_teilleisungsarten_with_missing_gewichtung(): void
    {
        $data = $this->data();
        unset($data['teilleistungsarten'][0]['gewichtung']);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not create teilleistungsarten when "Gewichtung" is null
     *
     * @return void
     */
    public function test_it_does_not_create_teilleisungsarten_with_null_gewichtung(): void
    {
        $data = $this->data();
        $data['teilleistungsarten'][0]['gewichtung'] = null;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create "Teilleistungsarten" with non-numeric "Gewichtung" */
    public function test_it_does_not_create_teilleisungsarten_with_non_numeric_gewichtung(): void
    {
        $data = $this->data();
        $data['teilleistungsarten'][0]['gewichtung'] = 'x';

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create "Teilleistungsarten" with missing "Sortierung" */
    public function test_it_does_not_create_teilleisungsarten_with_missing_sortierung(): void
    {
        $data = $this->data();
        unset($data['teilleistungsarten'][0]['sortierung']);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create "Teilleistungsarten" with empty "Sortierng" */
    public function test_it_does_not_create_teilleisungsarten_with_null_sortierung(): void
    {
        $data = $this->data();
        $data['teilleistungsarten'][0]['sortierung'] = null;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create "Teilleistungsarten" with non-numeric "Sortierung" */
    public function test_it_does_not_create_teilleisungsarten_with_non_numeric_sortierung(): void
    {
        $data = $this->data();
        $data['teilleistungsarten'][0]['sortierung'] = 'x';

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create "Teilleistungsarten" with already existsing "Sortierung" */
    public function test_it_does_not_create_teilleisungsarten_with_existing_sortierung(): void
    {
        Teilleistungsart::factory()->create(['sortierung' => 5]);

        $data = $this->data();
        $data['teilleistungsarten'][0]['sortierung'] = 5;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1);
    }
}
