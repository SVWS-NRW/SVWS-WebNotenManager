<?php

namespace Tests\Feature\Import;

use App\Models\{Klasse, User};
use App\Models\Jahrgang;
use App\Services\DataImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImportKlassenTest extends TestCase
{
    use RefreshDatabase;

    public const TABLE = 'klassen';
    public const RELATED_TABLE = 'klasse_user';

    /**
     * Creates Klasse with Klassenlehrer assigned
     *
     * @return void
     */
    public function test_it_creates_klasse_with_klassenlehrer_assigned(): void
    {
        Jahrgang::factory()->create(['id' => 16]);

        // Prepare Lehrer before importing
        collect([100, 101])->each(fn (int $id): User => User::factory()->create(['id' => $id, 'ext_id' => $id]));

        $data = json_decode('{
            "klassen": [
                {
                    "id": 61,
                    "kuerzel": "Q2",
                    "kuerzelAnzeige": "Q2",
                    "idJahrgang": 16,
                    "sortierung": 150,
                    "klassenlehrer": [
                        100, 101
                    ]
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, [
                'id' => 61,
                'kuerzel' => "Q2",
                'kuerzelAnzeige' => "Q2",
                'idJahrgang' => 16,
                'sortierung' => 150,
            ])
            ->assertDatabaseHas(self::RELATED_TABLE, [
                'klasse_id' => 61,
                'user_id' => 100,
            ])
            ->assertDatabaseHas(self::RELATED_TABLE, [
                'klasse_id' => 61,
                'user_id' => 101,
            ])
            ->assertDatabaseCount(self::RELATED_TABLE, 2);
    }

    /**
     * Creates klasse with related klassenlehrer only if klassenlehrer exists
     *
     * @return void
     */
    public function test_it_creates_klasse_with_related_klassenlehrer_only_if_klassenlehrer_exists(): void
    {
        Jahrgang::factory()->create(['id' => 16]);

        // Prepare Lehrer before importing
        collect([100, 101])->each(fn (int $id): User => User::factory()->create(['id' => $id, 'ext_id' => $id]));

        $data = json_decode('{
            "klassen": [
                {
                    "id": 61,
                    "kuerzel": "Q2",
                    "kuerzelAnzeige": "Q2",
                    "idJahrgang": 16,
                    "sortierung": 150,
                    "klassenlehrer": [
                        100, 102
                    ]
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::RELATED_TABLE, 1)
            ->assertDatabaseHas(self::RELATED_TABLE, [
                'klasse_id' => 61,
                'user_id' => 100,
            ])
            ->assertDatabaseMissing(self::RELATED_TABLE, [
                'klasse_id' => 61,
                'user_id' => 102,
            ]);
    }

    /**
     * Does not create Klasse without Klassenlehrer assigned
     *
     * @return void
     */
    public function test_it_does_not_create_klasse_without_klassenlehrer_assigned(): void
    {
        Jahrgang::factory()->create(['id' => 16]);

        $data = json_decode('{
            "klassen": [
                {
                    "id": 61,
                    "kuerzel": "Q2",
                    "kuerzelAnzeige": "Q2",
                    "idJahrgang": 16,
                    "sortierung": 150,
                    "klassenlehrer": []
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0)
            ->assertDatabaseCount(self::RELATED_TABLE, 0);
    }

    /**
     * Does not create Klasse without Klassenlehrer null
     *
     * @return void
     */
    public function test_it_does_not_create_klasse_without_klassenlehrer_null(): void
    {
        Jahrgang::factory()->create(['id' => 16]);

        $data = json_decode('{
            "klassen": [
                {
                    "id": 61,
                    "kuerzel": "Q2",
                    "kuerzelAnzeige": "Q2",
                    "idJahrgang": 16,
                    "sortierung": 150,
                    "klassenlehrer": null
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0)
            ->assertDatabaseCount(self::RELATED_TABLE, 0);
    }

    /**
     * Does not create Klasse without Klassenlehrer object
     *
     * @return void
     */
    public function test_it_does_not_create_klasse_without_klassenlehrer_object(): void
    {
        Jahrgang::factory()->create(['id' => 16]);

        $data = json_decode('{
            "klassen": [
                {
                    "id": 61,
                    "kuerzel": "Q2",
                    "kuerzelAnzeige": "Q2",
                    "idJahrgang": 16,
                    "sortierung": 150
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0)
            ->assertDatabaseCount(self::RELATED_TABLE, 0);
    }

    /**
     * Does not update Klasse
     *
     * @return void
     */
    public function test_it_does_not_update_klasse(): void
    {
        Jahrgang::factory()->create(['id' => 16]);

        $klasse = Klasse::factory()->create([
            'id' => 61,
            'kuerzel' => 'Q1',
            'kuerzelAnzeige' => 'Q1',
            'idJahrgang' => 15,
            'sortierung' => 140
        ]);

        $data = json_decode('{
            "klassen": [
                {
                    "id": 61,
                    "kuerzel": "Q2",
                    "kuerzelAnzeige": "Q2",
                    "idJahrgang": 16,
                    "sortierung": 150
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseHas(self::TABLE, [
            'id' => 61,
            'kuerzel' => 'Q1',
            'kuerzelAnzeige' => 'Q1',
            'idJahrgang' => 15,
            'sortierung' => 140
        ])->assertDatabaseMissing(self::TABLE, [
            'id' => 61,
            'kuerzel' => 'Q2',
            'kuerzelAnzeige' => 'Q2',
            'idJahrgang' => 16,
            'sortierung' => 150
        ]);
    }

    /**
     * Does not update Klassenlehrer
     *
     * @return void
     */
    public function test_it_does_not_update_klassenlehrer(): void
    {
        Jahrgang::factory()->create(['id' => 16]);

        Klasse::factory()
            ->has(User::factory(['id' => 1, 'ext_id' => 1]), 'klassenlehrer')
            ->create(['id' => 61]);

        User::factory(['id' => 2, 'ext_id' => 2])->create();

        $data = json_decode('{
            "klassen": [
                {
                    "id": 61,
                    "kuerzel": "Q2",
                    "kuerzelAnzeige": "Q2",
                    "idJahrgang": 16,
                    "sortierung": 150,
                    "klassenlehrer": [
                        2
                    ]
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::RELATED_TABLE, 1)
            ->assertDatabaseHas(self::RELATED_TABLE, [
                'klasse_id' => 61,
                'user_id' => 1,
            ])
            ->assertDatabaseMissing(self::RELATED_TABLE, [
                'klasse_id' => 61,
                'user_id' => 2,
            ]);
    }

    /**
     * It does not create klasse with negative id
     *
     * @return void
     */
    public function test_it_does_not_create_klasse_with_negative_id(): void
    {
        Jahrgang::factory()->create(['id' => 16]);

        $data = json_decode('{
            "klassen": [
                {
                    "id": -1,
                    "kuerzel": "negative",
                    "kuerzelAnzeige": "negative",
                    "idJahrgang": 16,
                    "sortierung": 150,
                    "klassenlehrer": [
                        2
                    ]
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseMissing(self::TABLE, [
            'kuerzel' => 'negative',
        ])->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not create klasse with non-integer id
     *
     * @return void
     */
    public function test_it_does_not_create_klasse_with_non_integer_id(): void
    {
        Jahrgang::factory()->create(['id' => 16]);

        $data = json_decode('{
            "klassen": [
                {
                    "id": "X",
                    "kuerzel": "negative",
                    "kuerzelAnzeige": "negative",
                    "idJahrgang": 16,
                    "sortierung": 150,
                    "klassenlehrer": [
                        2
                    ]
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not create klassen with missing id
     *
     * @return void
     */
    public function test_it_does_not_create_klasse_with_missing_id(): void
    {
        Jahrgang::factory()->create(['id' => 16]);

        $data = json_decode('{
            "klassen": [
                {
                    "kuerzel": "Q2",
                    "kuerzelAnzeige": "Q2",
                    "idJahrgang": 16,
                    "sortierung": 150,
                    "klassenlehrer": [
                        100, 101
                    ]
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not create klasse with empty id
     *
     * @return void
     */
    public function test_it_does_not_create_klasse_with_empty_id(): void
    {
        Jahrgang::factory()->create(['id' => 16]);

        $data = json_decode('{
            "klassen": [
                {
                    "id": null,
                    "kuerzel": "Q2",
                    "kuerzelAnzeige": "Q2",
                    "idJahrgang": 16,
                    "sortierung": 150,
                    "klassenlehrer": [
                        100, 101
                    ]
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not create klasse with existing Kuerzel
     *
     * @return void
     */
    public function test_it_does_not_create_klasse_with_existing_kurzel(): void
    {
        Jahrgang::factory()->create(['id' => 16]);

        Klasse::factory()->create([
            'kuerzel' => 'existingKuerzel',
        ]);

        $data = json_decode('{
            "klassen": [
                {
                    "id": 1,
                    "kuerzel": "existingKuerzel",
                    "kuerzelAnzeige": "Lorem ipsum",
                    "idJahrgang": 16,
                    "sortierung": 150,
                    "klassenlehrer": [
                        2
                    ]
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1);
    }

    /**
     * It does not create klasse with empty kuerzel
     *
     * @return void
     */
    public function test_it_does_not_create_klasse_with_empty_kuerzel(): void
    {
        Jahrgang::factory()->create(['id' => 16]);

        $data = json_decode('{
            "klassen": [
                {
                    "id": 61,
                    "kuerzel": null,
                    "kuerzelAnzeige": "Q2",
                    "idJahrgang": 16,
                    "sortierung": 150,
                    "klassenlehrer": [
                        100, 101
                    ]
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not create klasse with missing kuerzel
     *
     * @return void
     */
    public function test_it_does_not_create_klasse_with_missing_kuerzel(): void
    {
        Jahrgang::factory()->create(['id' => 16]);

        $data = json_decode('{
            "klassen": [
                {
                    "id": 61,
                    "kuerzelAnzeige": "Q2",
                    "idJahrgang": 16,
                    "sortierung": 150,
                    "klassenlehrer": [
                        100, 101
                    ]
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not create klasse with existing "Sortierung"
     *
     * @return void
     */
    public function test_it_does_not_create_klasse_with_existing_sortierung(): void
    {
        Jahrgang::factory()->create(['id' => 16]);

        Klasse::factory()->create([
            'sortierung' => 10,
        ]);

        $data = json_decode('{
            "klassen": [
                {
                    "id": 1,
                    "kuerzel": "existingKuerzel",
                    "kuerzelAnzeige": "Lorem ipsum",
                    "idJahrgang": 16,
                    "sortierung": 10,
                    "klassenlehrer": [
                        2
                    ]
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1);
    }

    /**
     * It does not create klasse with empty "Sortierung"
     *
     * @return void
     */
    public function test_it_does_not_create_klasse_with_empty_sortierung(): void
    {
        Jahrgang::factory()->create(['id' => 16]);

        $data = json_decode('{
            "klassen": [
                {
                    "id": 61,
                    "kuerzel": null,
                    "kuerzelAnzeige": "Q2",
                    "idJahrgang": 16,
                    "sortierung": null,
                    "klassenlehrer": [
                        100, 101
                    ]
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not create klasse with missing "Sortierung"
     *
     * @return void
     */
    public function test_it_does_not_create_klasse_with_missing_sortierung(): void
    {
        Jahrgang::factory()->create(['id' => 16]);

        $data = json_decode('{
            "klassen": [
                {
                    "id": 61,
                    "kuerzel": "Q2",
                    "kuerzelAnzeige": "Q2",
                    "idJahrgang": 16,
                    "klassenlehrer": [
                        100, 101
                    ]
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not create klasse with empty "Jahrgang"
     *
     * @return void
     */
    public function test_it_does_not_create_klasse_with_empty_jahrgang(): void
    {
        $data = json_decode('{
            "klassen": [
                {
                    "id": 61,
                    "kuerzel": "Q2",
                    "kuerzelAnzeige": "Q2",
                    "idJahrgang": null,
                    "sortierung": 10,
                    "klassenlehrer": [
                        100, 101
                    ]
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not create klasse with missing "Jahrgang"
     *
     * @return void
     */
    public function test_it_does_not_create_klasse_with_missing_jahrgang(): void
    {
        $data = json_decode('{
            "klassen": [
                {
                    "id": 61,
                    "kuerzel": "Q2",
                    "kuerzelAnzeige": "Q2",
                    "sortierung": 10,
                    "klassenlehrer": [
                        100, 101
                    ]
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * Creates klasse only if jahrgang exists
     *
     * @return void
     */
    public function test_id_does_not_create_klasse_with_non_existing_jahrgang_relation(): void
    {
        Jahrgang::factory()->create(['id' => 1]);

        // Prepare Lehrer before importing
        collect([100, 101])->each(fn (int $id): User => User::factory()->create(['id' => $id, 'ext_id' => $id]));

        $data = json_decode('{
            "klassen": [
                {
                    "id": 1,
                    "kuerzel": "Q1",
                    "kuerzelAnzeige": "Q1",
                    "idJahrgang": 1,
                    "sortierung": 10,
                    "klassenlehrer": [
                        100, 102
                    ]
                },
                {
                    "id": 2,
                    "kuerzel": "Q2",
                    "kuerzelAnzeige": "Q2",
                    "idJahrgang": 2,
                    "sortierung": 20,
                    "klassenlehrer": [
                        100, 102
                    ]
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, [
                'id' => 1,
            ])->assertDatabaseMissing(self::TABLE, [
                'id' => 2,
            ]);
    }

    /**
     * It returns when the "klassen" array is empty
     *
     * @return void
     */
    public function test_it_returns_when_the_klassen_array_is_missing(): void
    {
        $data = json_decode('{}', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It returns when the "klassen" array is empty
     *
     * @return void
     */
    public function test_it_returns_when_the_klassen_array_is_empty(): void
    {
        $data = json_decode('{
            "klassen": []
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }
}
