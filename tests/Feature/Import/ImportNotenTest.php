<?php

namespace Tests\Feature\Import;

use App\Models\Note;
use App\Services\DataImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImportNotenTest extends TestCase
{
    use RefreshDatabase;

    public const TABLE = 'noten';

    private function data(): array
    {
        return json_decode('{
            "noten": [
                {
                    "id": 1,
                    "kuerzel": "5-",
                    "notenpunkte": 1,
                    "text": "lorem"
                }
            ]
        }', true);
    }

    /** It creates noten */
    public function test_it_creates_noten(): void
    {
        (new DataImportService($this->data()))->execute();

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, [
                'id' => 1,
                'kuerzel' => '5-',
                'notenpunkte' => '1',
                'text' => 'lorem',
                'sortierung' => 1,
            ]);
    }

    /** It creates with text and notenpunkte missing */
    public function test_it_creates_with_text_and_notenpunkte_missing(): void
    {
        $data = $this->data();
        unset($data['noten'][0]['text']);
        unset($data['noten'][0]['notenpunkte']);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, [
                'id' => 1,
                'kuerzel' => '5-',
                'notenpunkte' => null,
                'text' => null,
            ]);
    }

    /** It creates with text and notenpunkte empty */
    public function test_it_creates_with_text_and_notenpunkte_empty(): void
    {
        $data = $this->data();
        $data['noten'][0]['text'] = '';
        $data['noten'][0]['notenpunkte'] = '';

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, [
                'id' => 1,
                'kuerzel' => '5-',
                'notenpunkte' => "",
                'text' => "",
            ]);
    }

    /** It creates with text and notenpunkte null */
    public function test_it_creates_with_text_and_notenpunkte_null(): void
    {
        $data = $this->data();
        $data['noten'][0]['text'] = null;
        $data['noten'][0]['notenpunkte'] = null;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, [
                'id' => 1,
                'kuerzel' => '5-',
                'notenpunkte' => null,
                'text' => null,
            ]);
    }

    /** It creates with negative id */
    public function test_it_creates_with_negative_id(): void
    {
        $data = $this->data();
        $data['noten'][0]['id'] = -1;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, [
                'id' => 1,
                'sortierung' => -1,
            ]);
    }

    /** It does not create with non-integer id */
    public function test_it_does_not_create_with_non_integer_id(): void
    {
        $data = $this->data();
        $data['noten'][0]['id'] = 'x';

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with existing Kuerzel */
    public function test_it_does_not_create_with_existing_kurzel(): void
    {
        Note::factory()->create(['kuerzel' => 'existingKuerzel']);

        $data = $this->data();
        $data['noten'][0]['kuerzel'] = 'existingKuerzel';

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1);
    }

    /** It does not create with missing id */
    public function test_it_does_not_create_with_missing_id(): void
    {
        $data = $this->data();
        unset($data['noten'][0]['id']);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with null "ID" */
    public function test_it_does_not_create_with_null_id(): void
    {
        $data = $this->data();
        $data['noten'][0]['id'] = null;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with empty id */
    public function test_it_does_not_create_with_empty_id(): void
    {
        $data = $this->data();
        $data['noten'][0]['id'] = '';

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }


    /** It does not create with empty kuerzel */
    public function test_it_does_not_create_with_empty_kuerzel(): void
    {
        $data = $this->data();
        $data['noten'][0]['kuerzel'] = '';

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with null kuerzel */
    public function test_it_does_not_create_with_null_kuerzel(): void
    {
        $data = $this->data();
        $data['noten'][0]['kuerzel'] = null;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not create with missing kuerzel */
    public function test_it_does_not_create_with_missing_kuerzel(): void
    {
        $data = $this->data();
        unset($data['noten'][0]['kuerzel']);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It does not update */
    public function test_it_does_not_update(): void
    {
        Note::factory()->create([
            'id' => 1,
            'kuerzel' => 'kuerzel',
            'notenpunkte' => 'old_notenpunkte',
            'text' => 'old_text',
            'sortierung' => 1,
        ]);

        $data = $this->data();
        $data['noten'][0]['kuerzel'] = '5-';

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, [
                'id' => 1,
                'kuerzel' => 'kuerzel',
                'notenpunkte' => 'old_notenpunkte',
                'text' => 'old_text',
                'sortierung' => 1,
            ])
            ->assertDatabaseMissing(self::TABLE, [
                'id' => 1,
                'kuerzel' => 'kuerzel',
                'notenpunkte' => 'new_notenpunkte',
                'text' => 'new_text',
                'sortierung' => 2,
            ]);
    }

    /** It returns when the array is missing */
    public function test_it_returns_when_the_array_is_missing(): void
    {
        $data = json_decode('{}', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It returns when array is empty */
    public function test_it_returns_when_the_array_is_empty(): void
    {
        $data = json_decode('{
            "noten": []
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }
}
