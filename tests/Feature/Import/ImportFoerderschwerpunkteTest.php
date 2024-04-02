<?php

namespace Tests\Feature\Import;

use App\Models\Foerderschwerpunkt;
use App\Services\DataImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImportFoerderschwerpunkteTest extends TestCase
{
    use RefreshDatabase;

    const TABLE = 'foerderschwerpunkte';

    /**
     * It creates foerderschwerpunkte
     *
     * @return void
     */
    public function test_it_creates_foerderschwerpunkte(): void
    {
        $data = json_decode('{
            "foerderschwerpunkte": [
                {
                    "id": 1,
                    "kuerzel": "5-",
                    "beschreibung": "lorem"
                }
            ]
        }', true);

        new DataImportService($data);

        $this->assertDatabaseHas('foerderschwerpunkte', [
            'id' => 1,
            'kuerzel' => '5-',
            'beschreibung' => 'lorem',
        ])->assertDatabaseCount(self::TABLE, 1);
    }

    /**
     * It does not update foerderschwerpunkte
     *
     * @return void
     */
    public function test_it_does_not_update_foerderschwerpunkte(): void
    {
        $note = Foerderschwerpunkt::factory()->create([
            'id' => 1,
            'kuerzel' => 'old_kuerzel',
            'beschreibung' => 'old_text'
        ]);

        $data = json_decode('{
            "foerderschwerpunkte": [
                {
                    "id": 1,
                    "kuerzel": "new_kuerzel",
                    "beschreibung": "new_text"
                }
            ]
        }', true);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, [
                'id' => 1,
                'kuerzel' => 'old_kuerzel',
                'beschreibung' => 'old_text',
            ])
            ->assertDatabaseMissing(self::TABLE, [
                'id' => 1,
                'kuerzel' => 'new_kuerzel',
                'beschreibung' => 'new_text',
            ]);
    }
    /**
     * It does not create foerderschwerpunkte with negative id
     *
     * @return void
     */
    public function test_it_does_not_create_foerderschwerpunkte_with_negative_id(): void
    {
        $data = json_decode('{
            "foerderschwerpunkte": [
                 {
                    "id": -1,
                    "kuerzel": "negative",
                    "beschreibung": "negative"
                },
                {
                    "id": 1,
                    "kuerzel": "5-",
                    "beschreibung": "Lorem ipsum dolor sit amet"
                }
            ]
        }', true);

        new DataImportService($data);

        $this->assertDatabaseMissing(self::TABLE, [
            'kuerzel' => 'negative',
            'beschreibung' => 'negative',
        ])->assertDatabaseCount(self::TABLE, 1);
    }

    /**
     * It does not create foerderschwerpunkte with non-integer id
     *
     * @return void
     */
    public function test_it_does_not_create_foerderschwerpunkte_with_non_integer_id(): void
    {
        $data = json_decode('{
            "foerderschwerpunkte": [
                 {
                    "id": "Z",
                    "kuerzel": "51",
                    "beschreibung": "Lorem ipsum dolor sit amet"
                }
            ]
        }', true);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not create foerderschwerpunkte with missing id
     *
     * @return void
     */
    public function test_it_does_not_create_foerderschwerpunkte_with_missing_id(): void
    {
        $data = json_decode('{
            "foerderschwerpunkte": [
                {
                    "kuerzel": "test"
                }
            ]
        }', true);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not create foerderschwerpunkte with empty id
     *
     * @return void
     */
    public function test_it_does_not_create_foerderschwerpunkte_with_empty_id(): void
    {
        $data = json_decode('{
            "foerderschwerpunkte": [
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
     * It does not create foerderschwerpunkte with existing Kuerzel
     *
     * @return void
     */
    public function test_it_does_not_create_foerderschwerpunkte_with_existing_kurzel(): void
    {
        $note = Foerderschwerpunkt::factory()->create([
            'kuerzel' => 'existingKuerzel',
        ]);

        $data = json_decode('{
            "foerderschwerpunkte": [
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
     * It does not create foerderschwerpunkte with empty kuerzel
     *
     * @return void
     */
    public function test_it_does_not_create_foerderschwerpunkte_with_empty_kuerzel(): void
    {
        $data = json_decode('{
            "foerderschwerpunkte": [
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
     * It does not create foerderschwerpunkte with missing kuerzel
     *
     * @return void
     */
    public function test_it_does_not_create_foerderschwerpunkte_with_missing_kuerzel(): void
    {
        $data = json_decode('{
            "foerderschwerpunkte": [
                {
                    "id": 1
                }
            ]
        }', true);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not create foerderschwerpunkte with beschreibung missing
     *
     * @return void
     */
    public function test_it_does_not_create_foerderschwerpunkte_with_beschreibung_missing(): void
    {
        $data = json_decode('{
            "foerderschwerpunkte": [
                {
                    "id": 1,
                    "kuerzel": "5-"
                }
            ]
        }', true);

        new DataImportService($data);

        $this->assertDatabaseMissing(self::TABLE, [
            'id' => 1,
            'kuerzel' => '5-',
            'beschreibung' => null,
        ])->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not create foerderschwerpunkte with beschreibung null
     *
     * @return void
     */
    public function test_it_does_not_create_foerderschwerpunkte_with_beschreibung_null(): void
    {
        $data = json_decode('{
            "foerderschwerpunkte": [
                {
                    "id": 1,
                    "kuerzel": "5-",
                    "beschreibung": null
                }
            ]
        }', true);

        new DataImportService($data);

        $this->assertDatabaseMissing(self::TABLE, [
            'id' => 1,
            'kuerzel' => '5-',
            'beschreibung' => null,
        ])
        ->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It returns when the "foerderschwerpunkte" array is empty
     *
     * @return void
     */
    public function test_it_returns_when_the_foerderschwerpunkte_array_is_missing(): void
    {
        $data = json_decode('{}', true);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It returns when the "foerderschwerpunkte" array is empty
     *
     * @return void
     */
    public function test_it_returns_when_the_foerderschwerpunkte_array_is_empty(): void
    {
        $data = json_decode('{
            "foerderschwerpunkte": []
        }', true);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 0);
    }
}
