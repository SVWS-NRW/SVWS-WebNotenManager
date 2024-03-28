<?php

namespace Tests\Feature\Import;

use App\Models\Jahrgang;
use App\Services\DataImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImportJahrgaengeTest extends TestCase
{
    use RefreshDatabase;

    const TABLE = 'jahrgaenge';

    /**
     * It creates jahrgaenge
     *
     * @return void
     */
    public function test_it_creates_jahrgaenge(): void
    {
        $data = json_decode('{
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

        new DataImportService($data);

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

    /**
     * It does not create jahrgaenge with id missing
     *
     * @return void
     */
    public function test_it_does_note_create_jahrgaenge_with_id_missing(): void
    {
        $data = json_decode('{
            "jahrgaenge": [
                {
                    "kuerzel": "Q2",
                    "kuerzelAnzeige": "Q2",
                    "beschreibung": "Qualifikationsphase 2",
                    "stufe": "SII-3",
                    "sortierung": 12
                }
            ]
        }', true);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not create jahrgaenge with null id
     *
     * @return void
     */
    public function test_it_does_note_create_jahrgaenge_with_null_id(): void
    {
        $data = json_decode('{
            "jahrgaenge": [
                {
                    "id": null,
                    "kuerzel": "Q2",
                    "kuerzelAnzeige": "Q2",
                    "beschreibung": "Qualifikationsphase 2",
                    "stufe": "SII-3",
                    "sortierung": 12
                }
            ]
        }', true);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not create jahrgaenge with negative id
     *
     * @return void
     */
    public function test_it_does_note_create_jahrgaenge_with_negative_id(): void
    {
        $data = json_decode('{
            "jahrgaenge": [
                {
                    "id": -1,
                    "kuerzel": "Q2",
                    "kuerzelAnzeige": "Q2",
                    "beschreibung": "Qualifikationsphase 2",
                    "stufe": "SII-3",
                    "sortierung": 12
                }
            ]
        }', true);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not create jahrgaenge with existing id
     *
     * @return void
     */
    public function test_it_does_note_create_jahrgaenge_with_existing_id(): void
    {
        Jahrgang::factory()->create([
            'id' => 1,
            'kuerzel' => 'Q2',
            'kuerzelAnzeige' => 'Q2',
            'beschreibung' => 'Qualifikationsphase 2',
            'stufe' => 'SII-3',
            'sortierung' => 12
        ]);

        $data = json_decode('{
            "jahrgaenge": [
                {
                    "id": 1,
                    "kuerzel": "Q3",
                    "kuerzelAnzeige": "Q3",
                    "beschreibung": "Qualifikationsphase 3",
                    "stufe": "SII-4",
                    "sortierung": 14
                }
            ]
        }', true);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, [
                'id' => 1,
                'kuerzel' => 'Q2',
            ])->assertDatabaseMissing(self::TABLE, [
                'id' => 1,
                'kuerzel' => 'Q3',
            ]);
    }

    /**
     * It does not create jahrgaenge with kuerzel missing
     *
     * @return void
     */
    public function test_it_does_note_create_jahrgaenge_with_kuerzel_missing(): void
    {
        $data = json_decode('{
            "jahrgaenge": [
                {
                    "id": 1,
                    "kuerzelAnzeige": "Q2",
                    "beschreibung": "Qualifikationsphase 2",
                    "stufe": "SII-3",
                    "sortierung": 12
                }
            ]
        }', true);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not create jahrgaenge with null kuerzel
     *
     * @return void
     */
    public function test_it_does_note_create_jahrgaenge_with_null_kuerzel(): void
    {
        $data = json_decode('{
            "jahrgaenge": [
                {
                    "id": 1,
                    "kuerzel": null,
                    "kuerzelAnzeige": "Q2",
                    "beschreibung": "Qualifikationsphase 2",
                    "stufe": "SII-3",
                    "sortierung": 12
                }
            ]
        }', true);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not create jahrgaenge with existing kuerzel
     *
     * @return void
     */
    public function test_it_does_note_create_jahrgaenge_with_existing_kuerzel(): void
    {
        Jahrgang::factory()->create([
            'kuerzel' => 'Q2',
        ]);

        $data = json_decode('{
            "jahrgaenge": [
                {
                    "id": 1,
                    "kuerzel": "Q3",
                    "kuerzelAnzeige": "Q3",
                    "beschreibung": "Qualifikationsphase 3",
                    "stufe": "SII-4",
                    "sortierung": 14
                }
            ]
        }', true);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, [
                'kuerzel' => 'Q2',
            ])->assertDatabaseMissing(self::TABLE, [
                'kuerzel' => 'Q3',
            ]);
    }

    /**
     * It does not create jahrgaenge with kuerzelAnzeige missing
     *
     * @return void
     */
    public function test_it_does_note_create_jahrgaenge_with_kuerzel_anzeige_missing(): void
    {
        $data = json_decode('{
            "jahrgaenge": [
                {
                    "id": 1,
                    "kuerzel": "Q2",
                    "beschreibung": "Qualifikationsphase 2",
                    "stufe": "SII-3",
                    "sortierung": 12
                }
            ]
        }', true);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not create jahrgaenge with null kuerzelAnzeige
     *
     * @return void
     */
    public function test_it_does_note_create_jahrgaenge_with_null_kuerzel_anzeige(): void
    {
        $data = json_decode('{
            "jahrgaenge": [
                {
                    "id": 1,
                    "kuerzel": "Q2",
                    "kuerzelAnzeige": null,
                    "beschreibung": "Qualifikationsphase 2",
                    "stufe": "SII-3",
                    "sortierung": 12
                }
            ]
        }', true);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not create jahrgaenge with beschreibung missing
     *
     * @return void
     */
    public function test_it_does_note_create_jahrgaenge_with_beschreibung_missing(): void
    {
        $data = json_decode('{
            "jahrgaenge": [
                {
                    "id": 1,
                    "kuerzel": "Q2",
                    "kuerzelAnzeige": "Q2",
                    "stufe": "SII-3",
                    "sortierung": 12
                }
            ]
        }', true);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not create jahrgaenge with null kuerzelAnzeige
     *
     * @return void
     */
    public function test_it_does_note_create_jahrgaenge_with_null_beschreibung(): void
    {
        $data = json_decode('{
            "jahrgaenge": [
                {
                    "id": 1,
                    "kuerzel": "Q2",
                    "kuerzelAnzeige": "Q2",
                    "beschreibung": null,
                    "stufe": "SII-3",
                    "sortierung": 12
                }
            ]
        }', true);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It creates jahrgaenge with trimmed whitespaces in beschreibung
     *
     * @return void
     */
    public function test_it_creates_jahrgaenge_with_trimmed_whitespaces_in_beschreibung(): void
    {
        $data = json_decode('{
            "jahrgaenge": [
                {
                    "id": 1,
                    "kuerzel": "Q2",
                    "kuerzelAnzeige": "Q2",
                    "beschreibung": "     Qualifikationsphase 2      ",
                    "stufe": "SII-3",
                    "sortierung": 12
                }
            ]
        }', true);

        new DataImportService($data);

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

    /**
     * It does not create jahrgaenge with stufe missing
     *
     * @return void
     */
    public function test_it_does_note_create_jahrgaenge_with_stufe_missing(): void
    {
        $data = json_decode('{
            "jahrgaenge": [
                {
                    "id": 1,
                    "kuerzel": "Q2",
                    "kuerzelAnzeige": "Q2",
                    "beschreibung": "Qualifikationsphase 2",
                    "sortierung": 12
                }
            ]
        }', true);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not create jahrgaenge with null stufe
     *
     * @return void
     */
    public function test_it_does_note_create_jahrgaenge_with_null_stufe(): void
    {
        $data = json_decode('{
            "jahrgaenge": [
                {
                    "id": 1,
                    "kuerzel": "Q2",
                    "kuerzelAnzeige": "Q2",
                    "beschreibung": "Qualifikationsphase 2",
                    "stufe": null,
                    "sortierung": 12
                }
            ]
        }', true);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not create jahrgaenge with sortierung missing
     *
     * @return void
     */
    public function test_it_does_note_create_jahrgaenge_with_sortierung_missing(): void
    {
        $data = json_decode('{
            "jahrgaenge": [
                {
                    "id": 1,
                    "kuerzel": "Q2",
                    "kuerzelAnzeige": "Q2",
                    "beschreibung": "Qualifikationsphase 2",
                    "stufe": "SII-3"
                }
            ]
        }', true);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not create jahrgaenge with null sortierung
     *
     * @return void
     */
    public function test_it_does_note_create_jahrgaenge_with_null_sortierung(): void
    {
        $data = json_decode('{
            "jahrgaenge": [
                {
                    "id": 1,
                    "kuerzel": "Q2",
                    "kuerzelAnzeige": "Q2",
                    "beschreibung": "Qualifikationsphase 2",
                    "stufe": "SII-3",
                    "sortierung": null
                }
            ]
        }', true);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not create jahrgaenge with existing sortierung
     *
     * @return void
     */
    public function test_it_does_note_create_jahrgaenge_with_existing_sortierung(): void
    {
        Jahrgang::factory()->create([
            'id' => 1,
            'sortierung' => 12,
        ]);

        $data = json_decode('{
            "jahrgaenge": [
                {
                    "id": 2,
                    "kuerzel": "Q2",
                    "kuerzelAnzeige": "Q2",
                    "beschreibung": "Qualifikationsphase 2",
                    "stufe": "SII-3",
                    "sortierung": 12
                }
            ]
        }', true);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseMissing(self::TABLE, [
                'id' => 2,
                'sortierung' => 12
            ]);
    }
}
