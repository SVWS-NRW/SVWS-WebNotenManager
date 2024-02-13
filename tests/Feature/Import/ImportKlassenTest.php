<?php

namespace Tests\Feature\Import;

use App\Models\Klasse;
use App\Models\User;
use App\Services\DataImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImportKlassenTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Creates Klasse with Klassenlehrer assigned
     *
     * @return void
     */
    public function test_it_creates_klasse_with_klassenlehrer_assigned(): void
    {
        // Prepare Lehrer before importing
        collect([100, 101])->each(fn (int $id): User =>
            User::factory()->create(['id' => $id, 'ext_id' => $id])
        );

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

        new DataImportService($data);

        $this->assertDatabaseHas('klassen', [
            'id' => 61,
            'kuerzel' => "Q2",
            'kuerzelAnzeige' => "Q2",
            'idJahrgang' => 16,
            'sortierung' => 150,
        ])
        ->assertDatabaseCount('klassen', 1)
        ->assertDatabaseHas('klasse_user', [
            'klasse_id' => 61,
            'user_id' => 100,
        ])
        ->assertDatabaseHas('klasse_user', [
            'klasse_id' => 61,
            'user_id' => 101,
        ])
        ->assertDatabaseCount('klasse_user', 2);
    }

    /**
     * Creates Klasse without Klassenlehrer if they were not created previously
     *
     * @return void
     */
    public function test_it_creates_klasse_without_klassenlehrer_if_they_were_not_created_previously(): void
    {
        // Prepare Lehrer before importing
        collect([100, 101])->each(fn (int $id): User =>
            User::factory()->create(['id' => $id, 'ext_id' => $id])
        );

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

        new DataImportService($data);

        $this->assertDatabaseCount('klasse_user', 1)
            ->assertDatabaseHas('klasse_user', [
                'klasse_id' => 61,
                'user_id' => 100,
            ])
            ->assertDatabaseMissing('klasse_user', [
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

        new DataImportService($data);

        $this->assertDatabaseCount('klassen', 0)
            ->assertDatabaseCount('klasse_user', 0);
    }

    /**
     * Does not create Klasse without Klassenlehrer object
     *
     * @return void
     */
    public function test_it_does_not_create_klasse_without_klassenlehrer_object(): void
    {
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

        new DataImportService($data);

        $this->assertDatabaseCount('klassen', 0)
            ->assertDatabaseCount('klasse_user', 0);
    }

    /**
     * Does not update Klasse
     *
     * @return void
     */
    public function test_it_does_not_update_klasse(): void
    {
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

        new DataImportService($data);

        $this->assertDatabaseHas('klassen', [
            'id' => 61,
            'kuerzel' => 'Q1',
            'kuerzelAnzeige' => 'Q1',
            'idJahrgang' => 15,
            'sortierung' => 140
        ])->assertDatabaseMissing('klassen', [
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
        $klasse = Klasse::factory()
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

        new DataImportService($data);

        $this->assertDatabaseCount('klasse_user', 1)
            ->assertDatabaseHas('klasse_user', [
                'klasse_id' => 61,
                'user_id' => 1,
            ])
            ->assertDatabaseMissing('klasse_user', [
                'klasse_id' => 61,
                'user_id' => 2,
            ]);
    }
}
