<?php

namespace Tests\Feature\Import;

use App\Models\Foerderschwerpunkt;
use App\Services\DataImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImportFoerderschwerpunkteTest extends TestCase
{
    use RefreshDatabase;

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
        ])
        ->assertDatabaseCount('foerderschwerpunkte', 1);
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

        $this->assertDatabaseMissing('foerderschwerpunkte', [
            'kuerzel' => 'negative',
            'beschreibung' => 'negative',
        ])
        ->assertDatabaseCount('foerderschwerpunkte', 1);
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

        $this->assertDatabaseCount('foerderschwerpunkte', 0);
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

        $this->assertDatabaseMissing('foerderschwerpunkte', [
            'id' => 1,
            'kuerzel' => '5-',
            'beschreibung' => null,
        ])
        ->assertDatabaseCount('foerderschwerpunkte', 0);
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

        $this->assertDatabaseMissing('foerderschwerpunkte', [
            'id' => 1,
            'kuerzel' => '5-',
            'beschreibung' => null,
        ])
        ->assertDatabaseCount('foerderschwerpunkte', 0);
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

        $this->assertDatabaseCount('foerderschwerpunkte', 1);
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

        $this->assertDatabaseCount('foerderschwerpunkte', 0);
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

        $this->assertDatabaseCount('foerderschwerpunkte', 0);
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

        $this->assertDatabaseCount('foerderschwerpunkte', 0);
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

        $this->assertDatabaseCount('foerderschwerpunkte', 0);
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

        $this->assertDatabaseCount('foerderschwerpunkte', 1)
            ->assertDatabaseHas('foerderschwerpunkte', [
                'id' => 1,
                'kuerzel' => 'old_kuerzel',
                'beschreibung' => 'old_text',
            ])
            ->assertDatabaseMissing('foerderschwerpunkte', [
                'id' => 1,
                'kuerzel' => 'new_kuerzel',
                'beschreibung' => 'new_text',
            ]);
    }
}
