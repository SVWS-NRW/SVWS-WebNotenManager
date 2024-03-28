<?php

namespace Tests\Feature\Import;

use App\Models\Note;
use App\Services\DataImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImportNotenTest extends TestCase
{
    use RefreshDatabase;

    const TABLE = 'noten';

    /**
     * It creates noten
     *
     * @return void
     */
    public function test_it_creates_noten(): void
    {
        $data = json_decode('{
            "noten": [
                {
                    "id": 1,
                    "kuerzel": "5-",
                    "notenpunkte": 1,
                    "text": "lorem"
                }
            ]
        }', true);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, [
                'id' => 1,
                'kuerzel' => '5-',
                'notenpunkte' => '1',
                'text' => 'lorem',
            ]);
    }

    /**
     * It creates noten with text and notenpunkte missing
     *
     * @return void
     */
    public function test_it_creates_noten_with_text_and_notenpunkte_missing(): void
    {
        $data = json_decode('{
            "noten": [
                {
                    "id": 1,
                    "kuerzel": "5-"
                }
            ]
        }', true);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, [
                'id' => 1,
                'kuerzel' => '5-',
                'notenpunkte' => null,
                'text' => null,
            ]);
    }

    /**
     * It creates noten with text and notenpunkte null
     *
     * @return void
     */
    public function test_it_creates_noten_with_text_and_notenpunkte_null(): void
    {
        $data = json_decode('{
            "noten": [
                {
                    "id": 1,
                    "kuerzel": "5-",
                    "notenpunkte": null,
                    "text": null
                }
            ]
        }', true);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, [
                'id' => 1,
                'kuerzel' => '5-',
                'notenpunkte' => null,
                'text' => null,
            ]);
    }

    /**
     * It does not create noten with negative id
     *
     * @return void
     */
    public function test_it_does_not_create_noten_with_negative_id(): void
    {
        $data = json_decode('{
            "noten": [
                 {
                    "id": -1,
                    "kuerzel": "negative",
                    "notenpunkte": -1,
                    "text": "negative"
                },
                {
                    "id": 1,
                    "kuerzel": "5-",
                    "notenpunkte": 1,
                    "text": "Lorem ipsum dolor sit amet"
                }
            ]
        }', true);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseMissing(self::TABLE, [
                'kuerzel' => 'negative',
                'text' => 'negative',
            ]);
    }

    /**
     * It does not create noten with non-integer id
     *
     * @return void
     */
    public function test_it_does_not_create_noten_with_non_integer_id(): void
    {
        $data = json_decode('{
            "noten": [
                 {
                    "id": "Z",
                    "kuerzel": "51",
                    "notenpunkte": 1,
                    "text": "Lorem ipsum dolor sit amet"
                }
            ]
        }', true);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not create noten with existing Kuerzel
     *
     * @return void
     */
    public function test_it_does_not_create_noten_with_existing_kurzel(): void
    {
        Note::factory()->create([
            'kuerzel' => 'existingKuerzel',
        ]);

        $data = json_decode('{
            "noten": [
                {
                    "id": 1,
                    "kuerzel": "existingKuerzel"
                }
            ]
        }', true);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 1);
    }

    /**
     * It does not create noten with missing id
     *
     * @return void
     */
    public function test_it_does_not_create_note_with_missing_id(): void
    {
        $data = json_decode('{
            "noten": [
                {
                    "kuerzel": "test"
                }
            ]
        }', true);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not create note with empty id
     *
     * @return void
     */
    public function test_it_does_not_create_note_with_empty_id(): void
    {
        $data = json_decode('{
            "noten": [
                {
                    "id": null,
                    "kuerzel": "test"
                }
            ]
        }', true);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not create note with empty kuerzel
     *
     * @return void
     */
    public function test_it_does_not_create_note_with_empty_kuerzel(): void
    {
        $data = json_decode('{
            "noten": [
                {
                    "id": 1,
                    "kuerzel": null
                }
            ]
        }', true);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not create note with missing kuerzel
     *
     * @return void
     */
    public function test_it_does_not_create_note_with_missing_kuerzel(): void
    {
        $data = json_decode('{
            "noten": [
                {
                    "id": 1
                }
            ]
        }', true);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not update noten
     *
     * @return void
     */
    public function test_it_does_not_update_noten(): void
    {
        Note::factory()->create([
            'id' => 1,
            'kuerzel' => 'old_kuerzel',
            'notenpunkte' => 'old_notenpunkte',
            'text' => 'old_text'
        ]);

        $data = json_decode('{
            "noten": [
                {
                    "id": 1,
                    "kuerzel": "new_kuerzel",
                    "notenpunkte": "new_notenpunkte",
                    "text": "new_text"
                }
            ]
        }', true);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, [
                'id' => 1,
                'kuerzel' => 'old_kuerzel',
                'notenpunkte' => 'old_notenpunkte',
                'text' => 'old_text',
            ])
            ->assertDatabaseMissing(self::TABLE, [
                'id' => 1,
                'kuerzel' => 'new_kuerzel',
                'notenpunkte' => 'new_notenpunkte',
                'text' => 'new_text',
            ]);
    }
}
