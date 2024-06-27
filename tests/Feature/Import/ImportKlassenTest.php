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
     * Get data
     *
     * @return array
     */
    private function getData(): array
    {
        return json_decode('{
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
    }

    /** Test is creates */
    public function test_it_creates(): void
    {
        Jahrgang::factory()->create(['id' => 16]);

        // Prepare Lehrer before importing
        collect([100, 101])->each(fn (int $id): User => User::factory()->create(['id' => $id, 'ext_id' => $id]));

        (new DataImportService($this->getData()))->execute();

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseCount(self::RELATED_TABLE, 2)
            ->assertDatabaseHas(self::TABLE, [
                'id' => 61,
                'kuerzel' => "Q2",
                'kuerzelAnzeige' => "Q2",
                'idJahrgang' => 16,
                'sortierung' => 150,
            ])
            ->assertDatabaseHas(self::RELATED_TABLE, ['klasse_id' => 61, 'user_id' => 100])
            ->assertDatabaseHas(self::RELATED_TABLE, ['klasse_id' => 61, 'user_id' => 101]);
    }

    /** It creates with related klassenlehrer only if klassenlehrer exists */
    public function test_it_creates_with_related_klassenlehrer_only_if_klassenlehrer_exists(): void
    {
        Jahrgang::factory()->create(['id' => 16]);

        // Prepare Lehrer before importing
        collect([100, 102])->each(fn (int $id): User => User::factory()->create(['id' => $id, 'ext_id' => $id]));

        (new DataImportService($this->getData()))->execute();

        $this->assertDatabaseCount(self::RELATED_TABLE, 1)
            ->assertDatabaseHas(self::RELATED_TABLE, ['klasse_id' => 61, 'user_id' => 100])
            ->assertDatabaseMissing(self::RELATED_TABLE, ['klasse_id' => 61, 'user_id' => 102]);
    }

    /** Does not create without Klassenlehrer assigned */
    public function test_it_does_not_create_klasse_without_klassenlehrer_assigned(): void
    {
        Jahrgang::factory()->create(['id' => 16]);

        $data = $this->getData();
        $data['klassen'][0]['klassenlehrer'] = [];

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0)
            ->assertDatabaseCount(self::RELATED_TABLE, 0);
    }


    /** Does not create with Klassenlehrer empty */
    public function test_it_does_not_create_with_klassenlehrer_incorrect_type(): void
    {
        Jahrgang::factory()->create(['id' => 16]);

        $data = $this->getData();
        $data['klassen'][0]['klassenlehrer'] = '';

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0)
            ->assertDatabaseCount(self::RELATED_TABLE, 0);
    }

    /** Does not create with Klassenlehrer null */
    public function test_it_does_not_create_with_klassenlehrer_null(): void
    {
        Jahrgang::factory()->create(['id' => 16]);

        $data = $this->getData();
        $data['klassen'][0]['klassenlehrer'] = null;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0)
            ->assertDatabaseCount(self::RELATED_TABLE, 0);
    }

    /** Does not create without Klassenlehrer */
    public function test_it_does_not_create_without_klassenlehrer(): void
    {
        Jahrgang::factory()->create(['id' => 16]);

        $data = $this->getData();
        unset($data['klassen'][0]['klassenlehrer']);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0)
            ->assertDatabaseCount(self::RELATED_TABLE, 0);
    }

    /** Does not update  */
    public function test_it_does_not_update(): void
    {
        Jahrgang::factory()->create(['id' => 16]);

        $klasse = Klasse::factory()->create([
            'id' => 61,
            'kuerzel' => 'Q1',
            'kuerzelAnzeige' => 'Q1',
            'idJahrgang' => 16,
            'sortierung' => 140
        ]);

        $data = $this->getData();
        $data['klassen'][0]['kuerzel'] = 'Q2';
        $data['klassen'][0]['kuerzelAnzeige'] = 'Q2';

        (new DataImportService($data))->execute();

        $this->assertDatabaseHas(self::TABLE, ['id' => 61, 'kuerzel' => 'Q1', 'kuerzelAnzeige' => 'Q1'])
            ->assertDatabaseMissing(self::TABLE, ['id' => 61, 'kuerzel' => 'Q2', 'kuerzelAnzeige' => 'Q2']);
    }

    /** Does not update Klassenlehrer */
    public function test_it_does_not_update_klassenlehrer(): void
    {
        Jahrgang::factory()->create(['id' => 16]);

        Klasse::factory()
            ->has(User::factory(['id' => 1, 'ext_id' => 1]), 'klassenlehrer')
            ->create(['id' => 61]);

        User::factory(['id' => 2, 'ext_id' => 2])->create();

        $data = $this->getData();
        $data['klassen'][0]['klassenlehrer'] = [2];

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::RELATED_TABLE, 1)
            ->assertDatabaseHas(self::RELATED_TABLE, ['klasse_id' => 61, 'user_id' => 1])
            ->assertDatabaseMissing(self::RELATED_TABLE, ['klasse_id' => 61, 'user_id' => 2]);
    }

    /** It does not create with negative id */
    public function test_it_does_not_create_with_negative_id(): void
    {
        Jahrgang::factory()->create(['id' => 16]);

        $data = $this->getData();
        $data['klassen'][0]['id'] = -1;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0)
            ->assertDatabaseMissing(self::TABLE, ['kuerzel' => 'negative']);
    }

    /** It does not create with non-integer id
     */
    public function test_it_does_not_create_with_non_integer_id(): void
    {
        Jahrgang::factory()->create(['id' => 16]);

        $data = $this->getData();
        $data['klassen'][0]['id'] = "x";

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create klassen with missing id */
    public function test_it_does_not_create_with_missing_id(): void
    {
        Jahrgang::factory()->create(['id' => 16]);

        $data = $this->getData();
        unset($data['klassen'][0]['id']);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create klasse with null id */
    public function test_it_does_not_create_klasse_with_null_id(): void
    {
        Jahrgang::factory()->create(['id' => 16]);

        $data = $this->getData();
        $data['klassen'][0]['id'] = null;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create klasse with empty id */
    public function test_it_does_not_create_klasse_with_empty_id(): void
    {
        Jahrgang::factory()->create(['id' => 16]);

        $data = $this->getData();
        $data['klassen'][0]['id'] = '';

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with existing Kuerzel */
    public function test_it_does_not_create_with_existing_kurzel(): void
    {
        Jahrgang::factory()->create(['id' => 16]);

        Klasse::factory()->create(['kuerzel' => 'existingKuerzel']);

        $data = $this->getData();
        $data['klassen'][0]['kuerzel'] = 'existingKuerzel';

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1);
    }

    /** It does not create with null kuerzel */
    public function test_it_does_not_create_with_null_kuerzel(): void
    {
        Jahrgang::factory()->create(['id' => 16]);

        $data = $this->getData();
        $data['klassen'][0]['kuerzel'] = null;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with empty kuerzel */
    public function test_it_does_not_create_with_empty_kuerzel(): void
    {
        Jahrgang::factory()->create(['id' => 16]);

        $data = $this->getData();
        $data['klassen'][0]['kuerzel'] = '';

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with missing kuerzel */
    public function test_it_does_not_create_with_missing_kuerzel(): void
    {
        Jahrgang::factory()->create(['id' => 16]);

        $data = $this->getData();
        unset($data['klassen'][0]['kuerzel']);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with existing "Sortierung" */
    public function test_it_does_not_create__with_existing_sortierung(): void
    {
        Jahrgang::factory()->create(['id' => 16]);
        Klasse::factory()->create(['sortierung' => 10]);

        $data = $this->getData();
        $data['klassen'][0]['sortierung'] = 10;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1);
    }

    /**
     * It does not create with null "Sortierung"
     *
     * @return void
     */
    public function test_it_does_not_create_with_null_sortierung(): void
    {
        Jahrgang::factory()->create(['id' => 16]);

        $data = $this->getData();
        $data['klassen'][0]['sortierung'] = null;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It does not create with empty "Sortierung"
     *
     * @return void
     */
    public function test_it_does_not_create_with_empty_sortierung(): void
    {
        Jahrgang::factory()->create(['id' => 16]);

        $data = $this->getData();
        $data['klassen'][0]['sortierung'] = '';

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with missing "Sortierung" */
    public function test_it_does_not_create_with_missing_sortierung(): void
    {
        Jahrgang::factory()->create(['id' => 16]);

        $data = $this->getData();
        unset($data['klassen'][0]['sortierung']);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with null "Jahrgang" */
    public function test_it_does_not_create_with_null_jahrgang(): void
    {
        $data = $this->getData();
        $data['klassen'][0]['idJahrgang'] = null;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with negative "Jahrgang" */
    public function test_it_does_not_create_with_negative_jahrgang(): void
    {
        $data = $this->getData();
        $data['klassen'][0]['idJahrgang'] = -1;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with non-integer "Jahrgang" */
    public function test_it_does_not_create_with_non_integer_jahrgang(): void
    {
        $data = $this->getData();
        $data['klassen'][0]['idJahrgang'] = 'a';

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with empty "Jahrgang" */
    public function test_it_does_not_create_with_empty_jahrgang(): void
    {
        $data = $this->getData();
        $data['klassen'][0]['idJahrgang'] = '';

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with missing "Jahrgang" */
    public function test_it_does_not_create_with_missing_jahrgang(): void
    {
        $data = $this->getData();
        unset($data['klassen'][0]['idJahrgang']);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create if jahrgang does not exist */
    public function test_id_does_not_create_with_non_existing_jahrgang_relation(): void
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
                        100, 101
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
            ->assertDatabaseHas(self::TABLE, ['id' => 1
            ])->assertDatabaseMissing(self::TABLE, [
                'id' => 2,
            ]);
    }

    /** It returns when the "klassen" array is empty */
    public function test_it_returns_when_the_klassen_array_is_missing(): void
    {
        $data = json_decode('{}', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It returns when the "klassen" array is empty */
    public function test_it_returns_when_the_klassen_array_is_empty(): void
    {
        $data = json_decode('{
            "klassen": []
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It returns when the "klassen" array is null */
    public function test_it_returns_when_the_klassen_array_is_null(): void
    {
        $data = json_decode('{
            "klassen": null
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }
}
