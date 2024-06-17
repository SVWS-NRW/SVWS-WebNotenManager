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

    /** It creates "Teilleistungsarten" */
    public function test_it_creates_teilleistungsarten(): void
    {
        $data = json_decode('{
            "teilleistungsarten": [
                {
                    "id": 1,
                    "bezeichnung": "Klassenarbeit/Klausur",
                    "sortierung": 1,
                    "gewichtung": 1
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

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

        $data = json_decode('{
            "teilleistungsarten": [
                {
                    "id": 1,
                    "bezeichnung": "New value",
                    "sortierung": 2,
                    "gewichtung": 2
                }
            ]
        }', true);

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
        $data = json_decode('{
            "teilleistungsarten": [
                {
                    "id": 1,
                    "sortierung": 1,
                    "gewichtung": 1
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create "Teilleistungsarten" with empty "Bezeichnung" */
    public function test_it_does_not_create_teilleisungsarten_with_empty_bezeichnung(): void
    {
        $data = json_decode('{
            "teilleistungsarten": [
                {
                    "id": 1,
                    "bezeichnung": null,
                    "sortierung": 1,
                    "gewichtung": 1
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create "Teilleistungsarten" with already existsing "Bezeichnung" */
    public function test_it_does_not_create_teilleisungsarten_with_existing_bezeichnung(): void
    {
        Teilleistungsart::factory()->create(['bezeichnung' => 'Klassenarbeit/Klausur']);

        $data = json_decode('{
            "teilleistungsarten": [
                {
                    "id": 1,
                    "bezeichnung": "Klassenarbeit/Klausur",
                    "sortierung": 1,
                    "gewichtung": 1
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1);
    }

    /** It does not create "Teilleistungsarten" with missing "id" */
    public function test_it_does_not_create_teilleisungsarten_with_missing_id(): void
    {
        $data = json_decode('{
            "teilleistungsarten": [
                {
                    "bezeichnung": "Klassenarbeit/Klausur",
                    "sortierung": 1,
                    "gewichtung": 1
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create "Teilleistungsarten" with empty "id" */
    public function test_it_does_not_create_teilleisungsarten_with_empty_id(): void
    {
        $data = json_decode('{
            "teilleistungsarten": [
                {
                    "id": null,
                    "bezeichnung": "Klassenarbeit/Klausur",
                    "sortierung": 1,
                    "gewichtung": 1
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create "Teilleistungsarten" with non-numeric "id" */
    public function test_it_does_not_create_teilleisungsarten_with_non_numeric_id(): void
    {
        $data = json_decode('{
            "teilleistungsarten": [
                {
                    "id": "x",
                    "bezeichnung": "Klassenarbeit/Klausur",
                    "sortierung": 1,
                    "gewichtung": 1
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create "Teilleistungsarten" with missing "Gewichtung" */
    public function test_it_does_not_create_teilleisungsarten_with_missing_gewichtung(): void
    {
        $data = json_decode('{
            "teilleistungsarten": [
                {
                    "id": 1,
                    "bezeichnung": "Klassenarbeit/Klausur",
                    "sortierung": 1
                }
            ]
        }', true);

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
        $data = json_decode('{
            "teilleistungsarten": [
                {
                    "id": 1,
                    "bezeichnung": "Klassenarbeit/Klausur",
                    "sortierung": 1,
                    "gewichtung": null
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create "Teilleistungsarten" with non-numeric "Gewichtung" */
    public function test_it_does_not_create_teilleisungsarten_with_non_numeric_gewichtung(): void
    {
        $data = json_decode('{
            "teilleistungsarten": [
                {
                    "id": 1,
                    "bezeichnung": "Klassenarbeit/Klausur",
                    "sortierung": 1,
                    "gewichtung": "x"
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create "Teilleistungsarten" with missing "Sortierung" */
    public function test_it_does_not_create_teilleisungsarten_with_missing_sortierung(): void
    {
        $data = json_decode('{
            "teilleistungsarten": [
                {
                    "id": 1,
                    "bezeichnung": "Klassenarbeit/Klausur",
                    "gewichtung": 1
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create "Teilleistungsarten" with empty "Sortierng" */
    public function test_it_does_not_create_teilleisungsarten_with_null_sortierung(): void
    {
        $data = json_decode('{
            "teilleistungsarten": [
                {
                    "id": 1,
                    "bezeichnung": "Klassenarbeit/Klausur",
                    "sortierung": null,
                    "gewichtung": 1
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create "Teilleistungsarten" with non-numeric "Sortierung" */
    public function test_it_does_not_create_teilleisungsarten_with_non_numeric_sortierung(): void
    {
        $data = json_decode('{
            "teilleistungsarten": [
                {
                    "id": 1,
                    "bezeichnung": "Klassenarbeit/Klausur",
                    "sortierung": "x",
                    "gewichtung": 1
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create "Teilleistungsarten" with already existsing "Sortierung" */
    public function test_it_does_not_create_teilleisungsarten_with_existing_sortierung(): void
    {
        Teilleistungsart::factory()->create(['sortierung' => 5]);

        $data = json_decode('{
            "teilleistungsarten": [
                {
                    "id": 1,
                    "bezeichnung": "Klassenarbeit/Klausur",
                    "sortierung": 5,
                    "gewichtung": 1
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1);
    }
}
