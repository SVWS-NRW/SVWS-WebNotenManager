<?php

namespace Tests\Feature\Import;

use App\Models\Fach;
use App\Services\DataImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImportFaecherTest extends TestCase
{
    use RefreshDatabase;

    public const TABLE = 'faecher';

    /** It creates fach */
    public function test_it_creates(): void
    {
        $data = json_decode('{
            "faecher": [
                {
                    "id": 1,
                    "kuerzel": "M",
                    "kuerzelAnzeige": "M",
                    "sortierung": 20,
                    "istFremdsprache": false
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseHas(self::TABLE, [
            'id' => 1,
            'kuerzel' => 'M',
            'kuerzelAnzeige' => 'M',
            'sortierung' => 20,
            'istFremdsprache' => false,
        ])
        ->assertDatabaseCount('faecher', 1);
    }

    /** It does not update */
    public function test_it_does_not_update(): void
    {
        Fach::factory()->create([
            'id' => 1,
            'kuerzel' => 'old_kuerzel',
        ]);

        $data = json_decode('{
            "faecher": [
                {
                    "id": 1,
                    "kuerzel": "new_kuerzel",
                    "kuerzelAnzeige": "M",
                    "sortierung": 20,
                    "istFremdsprache": false
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, [
                'id' => 1,
                'kuerzel' => 'old_kuerzel',
            ])
            ->assertDatabaseMissing(self::TABLE, [
                'id' => 1,
                'kuerzel' => 'new_kuerzel',
            ]);
    }

    /** It does not create with negative "ID" */
    public function test_it_does_not_create_with_negative_id(): void
    {
        $data = json_decode('{
            "faecher": [
                {
                    "id": -1,
                    "kuerzel": "negative",
                    "kuerzelAnzeige": "M",
                    "sortierung": 20,
                    "istFremdsprache": false
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseMissing(self::TABLE, [
            'kuerzel' => 'negative',
        ])->assertDatabaseCount('noten', 0);
    }

    /** It does not create with non-integer "ID" */
    public function test_it_does_not_create_with_non_integer_id(): void
    {
        $data = json_decode('{
            "faecher": [
                {
                    "id": "X",
                    "kuerzel": "negative",
                    "kuerzelAnzeige": "M",
                    "sortierung": 20,
                    "istFremdsprache": false
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }


    /** It does not create with missing "ID" */
    public function test_it_does_not_create_with_missing_id(): void
    {
        $data = json_decode('{
            "faecher": [
                {
                    "kuerzel": "M",
                    "kuerzelAnzeige": "M",
                    "sortierung": 20,
                    "istFremdsprache": false
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create fach with empty "ID" */
    public function test_it_does_not_create_with_empty_id(): void
    {
        $data = json_decode('{
            "faecher": [
                {
                    "id": null,
                    "kuerzel": "M",
                    "kuerzelAnzeige": "M",
                    "sortierung": 20,
                    "istFremdsprache": false
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with existing Kuerzel */
    public function test_it_does_not_create_with_existing_kurzel(): void
    {
        Fach::factory()->create([
            'kuerzel' => 'existingKuerzel',
        ]);

        $data = json_decode('{
            "faecher": [
                {
                    "id": 2,
                    "kuerzel": "existingKuerzel",
                    "kuerzelAnzeige": "M",
                    "sortierung": 20,
                    "istFremdsprache": false
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1);
    }

    /** It does not create with empty kuerzel */
    public function test_it_does_not_create_with_empty_kuerzel(): void
    {
        $data = json_decode('{
            "faecher": [
                {
                    "id": 1,
                    "kuerzel": null,
                    "kuerzelAnzeige": "M",
                    "sortierung": 20,
                    "istFremdsprache": false
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with missing kuerzel */
    public function test_it_does_not_create_with_missing_kuerzel(): void
    {
        $data = json_decode('{
            "faecher": [
                {
                    "id": 1,
                    "kuerzelAnzeige": "M",
                    "sortierung": 20,
                    "istFremdsprache": false
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with missing sortierung */
    public function test_it_does_not_create_with_missing_sortierung(): void
    {
        $data = json_decode('{
            "faecher": [
                {
                    "id": 1,
                    "kuerzel": "M",
                    "kuerzelAnzeige": "M",
                    "istFremdsprache": false
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with empty sortierung */
    public function test_it_does_not_create_with_empty_sortierung(): void
    {
        $data = json_decode('{
            "faecher": [
                {
                    "id": 1,
                    "kuerzel": "M",
                    "kuerzelAnzeige": "M",
                    "sortierung": null,
                    "istFremdsprache": false
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with existing sortierung */
    public function test_it_does_not_create_with_existing_sortierung(): void
    {
        Fach::factory()->create([
            'sortierung' => 10,
        ]);

        $data = json_decode('{
            "faecher": [
                {
                    "id": 2,
                    "kuerzel": "M",
                    "kuerzelAnzeige": "M",
                    "sortierung": 10,
                    "istFremdsprache": false
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1);
    }

    /** It does not create with missing "istFremdsprache" */
    public function test_it_does_not_create_with_missing_istFremdsprache(): void
    {
        $data = json_decode('{
            "faecher": [
                {
                    "id": 1,
                    "kuerzel": "M",
                    "kuerzelAnzeige": "M",
                    "sortierung": 10
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with empty "istFremdsprache" */
    public function test_it_does_not_create_with_empty_istFremdsprache(): void
    {
        $data = json_decode('{
            "faecher": [
                {
                    "id": 1,
                    "kuerzel": "M",
                    "kuerzelAnzeige": "M",
                    "sortierung": 10,
                    "istFremdsprache": null
                }
            ]
        }', true);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It returns when the "faecher" array is empty */
    public function test_it_returns_when_the_faecher_array_is_missing(): void
    {
        $data = json_decode('{}', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It returns when the "faecher" array is empty */
    public function test_it_returns_when_the_faecher_array_is_empty(): void
    {
        $data = json_decode('{
            "faecher": []
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }
}
