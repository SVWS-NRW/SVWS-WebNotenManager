<?php

namespace Tests\Feature\Import;

use App\Models\Foerderschwerpunkt;
use App\Services\DataImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImportFoerderschwerpunkteTest extends TestCase
{
    use RefreshDatabase;

    public const TABLE = 'foerderschwerpunkte';

    /**
     * Get data
     *
     * @return array
     */
    private function getData(): array
    {
        return json_decode('{
            "foerderschwerpunkte": [
                {
                    "id": 1,
                    "kuerzel": "5-",
                    "beschreibung": "lorem"
                }
            ]
        }', true);
    }

    /** It creates */
    public function test_it_creates(): void
    {
        (new DataImportService($this->getData()))->execute();

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas('foerderschwerpunkte', [
                'id' => 1,
                'kuerzel' => '5-',
                'beschreibung' => 'lorem',
                'sortierung' => 1,
            ]);
    }

    /** It does not update */
    public function test_it_does_not_update(): void
    {
        Foerderschwerpunkt::factory()->create([
            'id' => 1,
            'kuerzel' => '5-',
            'beschreibung' => 'old_text',
            'sortierung' => 1,
        ]);

        $data = $this->getData();
        $data['foerderschwerpunkte'][0]['beschreibung'] = 'new_text';

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, [
                'id' => 1,
                'kuerzel' => '5-',
                'beschreibung' => 'old_text',
            ])
            ->assertDatabaseMissing(self::TABLE, [
                'id' => 1,
                'kuerzel' => '5-',
                'beschreibung' => 'new_text',
            ]);
    }

    /** It creates with negative id */
    public function test_it_creates_with_negative_id(): void
    {
        $data = $this->getData();
        $data['foerderschwerpunkte'][0]['id'] = -1;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1);
    }

    /** It does not create with non-integer id */
    public function test_it_does_not_create_with_non_integer_id(): void
    {
        $data = $this->getData();
        $data['foerderschwerpunkte'][0]['id'] = 'x';

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with missing id */
    public function test_it_does_not_create_with_missing_id(): void
    {
        $data = $this->getData();
        unset($data['foerderschwerpunkte'][0]['id']);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with empty id */
    public function test_it_does_not_create_with_empty_id(): void
    {
        $data = $this->getData();
        $data['foerderschwerpunkte'][0]['id'] = '';

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with null id */
    public function test_it_does_not_create_with_null_id(): void
    {
        $data = $this->getData();
        $data['foerderschwerpunkte'][0]['id'] = null;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with existing Kuerzel */
    public function test_it_does_not_create_with_existing_kurzel(): void
    {
        Foerderschwerpunkt::factory()->create(['kuerzel' => 'existingKuerzel']);

        $data = $this->getData();
        $data['foerderschwerpunkte'][0]['kuerzel'] = 'existingKuerzel';

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1);
    }

    /** It does not create with empty kuerzel */
    public function test_it_does_not_create_with_empty_kuerzel(): void
    {
        $data = $this->getData();
        $data['foerderschwerpunkte'][0]['kuerzel'] = '';

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with null kuerzel */
    public function test_it_does_not_create_with_null_kuerzel(): void
    {
        $data = $this->getData();
        $data['foerderschwerpunkte'][0]['kuerzel'] = null;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with missing kuerzel */
    public function test_it_does_not_create_with_missing_kuerzel(): void
    {
        $data = $this->getData();
        unset($data['foerderschwerpunkte'][0]['kuerzel']);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with beschreibung missing */
    public function test_it_does_not_create_with_beschreibung_missing(): void
    {
        $data = $this->getData();
        unset($data['foerderschwerpunkte'][0]['beschreibung']);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with beschreibung null */
    public function test_it_does_not_create_with_beschreibung_null(): void
    {
        $data = $this->getData();
        $data['foerderschwerpunkte'][0]['beschreibung'] = null;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It returns when the "foerderschwerpunkte" array is empty */
    public function test_it_returns_when_the_foerderschwerpunkte_array_is_missing(): void
    {
        $data = json_decode('{}', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It returns when the "foerderschwerpunkte" array is empty */
    public function test_it_returns_when_the_foerderschwerpunkte_array_is_empty(): void
    {
        $data = json_decode('{
            "foerderschwerpunkte": []
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }
}
