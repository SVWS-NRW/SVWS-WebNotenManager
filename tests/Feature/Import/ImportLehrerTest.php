<?php

namespace Tests\Feature\Import;

use App\Models\User;
use App\Services\DataImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImportLehrerTest extends TestCase
{
    use RefreshDatabase;

    public const TABLE = 'users';

    /**
     * Get data
     *
     * @return array
     */
    private function getData(): array
    {
        return json_decode('{
            "lehrer": [
                {
                    "id": 155,
                    "kuerzel": "ÖA",
                    "nachname": "Ölschläger",
                    "vorname": "Kevin",
                    "geschlecht": "m",
                    "eMailDienstlich": "öa@svws-nrw.de"
                }
            ]
        }', true);
    }

    /** Test it creates */
    public function test_it_creates(): void
    {
        $data = $this->getData();
        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, [
                'ext_id' => 155,
                'kuerzel' => 'ÖA',
                'nachname' => 'Ölschläger',
                'vorname' => 'Kevin',
                'geschlecht' => 'm',
                'email' => 'öa@svws-nrw.de'
            ]);
    }

    /** Test if creates with random email when email is missing */
    public function test_it_creates_with_random_email_when_email_is_missing(): void
    {
        $data = $this->getData();
        unset($data['lehrer'][0]['eMailDienstlich']);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseMissing(self::TABLE, ['eMailDienstlich' => '']);
    }

    /** Creates with random email when email was null */
    public function test_it_creates_with_random_email_when_email_was_null(): void
    {
        $data = $this->getData();
        $data['lehrer'][0]['eMailDienstlich'] = null;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseMissing(self::TABLE, ['eMailDienstlich' => '']);
    }

    /** Creates with random email when email was empty */
    public function test_it_creates_with_random_email_when_email_was_empty(): void
    {
        $data = $this->getData();
        $data['lehrer'][0]['eMailDienstlich'] = '';

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseMissing(self::TABLE, ['eMailDienstlich' => '']);
    }

    /** Creates with random email when invalid email provided */
    public function test_it_creates_with_random_email_when_invalid_email_provided(): void
    {
        $data = $this->getData();
        $data['lehrer'][0]['eMailDienstlich'] = 'inkorrekt#email';

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseMissing(self::TABLE, ['eMailDienstlich' => 'incorrect#email']);
    }

    /** Creates with fallback gender if no gender provided Fallback gender is defined in \App\Models\User::class */
    public function test_it_creates_with_fallback_gender_if_gender_not_provided(): void
    {
        $data = $this->getData();
        unset($data['lehrer'][0]['geschlecht']);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, ['geschlecht' => User::FALLBACK_GENDER])
            ->assertDatabaseMissing(self::TABLE, ['geschlecht' => 'incorrect']);
    }

    /** Creates with fallback if incorrect gender provided Fallback gender is defined in \App\Models\User::class */
    public function test_it_creates_lehrer_with_fallback_gender_if_incorrect_gender_provided(): void
    {
        $data = $this->getData();
        $data['lehrer'][0]['geschlecht'] = 'Z';

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, ['geschlecht' => User::FALLBACK_GENDER])
            ->assertDatabaseMissing(self::TABLE, ['geschlecht' => '']);
    }

    /** Updates details */
    public function test_it_updates_details(): void
    {
        User::factory()->create([
            'ext_id' => 155,
            'kuerzel' => 'MM',
            'nachname' => 'Mustermann',
            'vorname' => 'Max',
            'geschlecht' => 'd',
            'email' => 'mm@svws-nrw.de'
        ]);

        $data = $this->getData();
        $data['lehrer'][0]['id'] = 155;
        $data['lehrer'][0]['kuerzel'] = "ÖA";
        $data['lehrer'][0]['nachname'] = "Ölschläger";
        $data['lehrer'][0]['vorname'] = "Kevin";
        $data['lehrer'][0]['geschlecht'] = "m";
        $data['lehrer'][0]['eMailDienstlich'] = "öa@svws-nrw.de";

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, [
                'ext_id' => 155,
                'kuerzel' => 'ÖA',
                'nachname' => 'Ölschläger',
                'vorname' => 'Kevin',
                'geschlecht' => 'm',
                'email' => 'öa@svws-nrw.de'
            ])
            ->assertDatabaseMissing(self::TABLE, [
                'ext_id' => 155,
                'kuerzel' => 'MM',
                'nachname' => 'Max',
                'vorname' => 'Mustermann',
                'geschlecht' => 'd',
                'email' => 'mm@svws-nrw.de'
            ]);
    }

    /** Does not update email when email has invalid format */
    public function test_it_does_not_update_email_when_email_has_invalid_format(): void
    {
        User::factory()->create([
            'ext_id' => 155,
            'email' => 'öa@svws-nrw.de'
        ]);

        $data = $this->getData();
        $data['lehrer'][0]['id'] = 155;
        $data['lehrer'][0]['eMailDienstlich'] = "invalid#email.com";

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, ['email' => 'öa@svws-nrw.de'])
            ->assertDatabaseMissing(self::TABLE, ['email' => 'invalid#email.com']);
    }

    /** Does not update email when email is missing */
    public function test_it_does_not_update_email_when_email_is_missing(): void
    {
        User::factory()->create([
            'ext_id' => 155,
            'email' => 'öa@svws-nrw.de'
        ]);

        $data = $this->getData();
        $data['lehrer'][0]['id'] = 155;
        unset($data['lehrer'][0]['eMailDienstlich']);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, ['email' => 'öa@svws-nrw.de'])
            ->assertDatabaseMissing(self::TABLE, ['email' => '']);
    }

    /** Does not update email when email is null */
    public function test_it_does_not_update_email_when_email_is_null(): void
    {
        User::factory()->create([
            'ext_id' => 155,
            'email' => 'öa@svws-nrw.de'
        ]);

        $data = $this->getData();
        $data['lehrer'][0]['id'] = 155;
        $data['lehrer'][0]['eMailDienstlich'] = null;

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, ['email' => 'öa@svws-nrw.de'])
            ->assertDatabaseMissing(self::TABLE, ['email' => '']);
    }

    /** Does not update email when email is empty */
    public function test_it_does_not_update_email_when_email_is_empty(): void
    {
        User::factory()->create([
            'ext_id' => 155,
            'email' => 'öa@svws-nrw.de'
        ]);

        $data = $this->getData();
        $data['lehrer'][0]['id'] = 155;
        $data['lehrer'][0]['eMailDienstlich'] = '';

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, ['email' => 'öa@svws-nrw.de'])
            ->assertDatabaseMissing(self::TABLE, ['email' => '']);
    }

    /** Does not update gender when gender is invalid */
    public function test_it_does_not_update_gender_when_gender_is_invalid(): void
    {
        User::factory()->create([
            'ext_id' => 155,
            'geschlecht' => 'd'
        ]);

        $data = $this->getData();
        $data['lehrer'][0]['id'] = 155;
        $data['lehrer'][0]['geschlecht'] = 'Z';

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, ['geschlecht' => 'd'])
            ->assertDatabaseMissing(self::TABLE, ['geschlecht' => 'Z']);
    }

    /** Does not update gender when gender is missing */
    public function test_it_does_not_update_gender_when_gender_is_missing(): void
    {
        User::factory()->create([
            'ext_id' => 155,
            'geschlecht' => 'd'
        ]);

        $data = $this->getData();
        $data['lehrer'][0]['id'] = 155;
        unset($data['lehrer'][0]['geschlecht']);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, ['geschlecht' => 'd'])
            ->assertDatabaseMissing(self::TABLE, ['geschlecht' => '']);
    }

    /** Does not update gender when gender is NULL */
    public function test_it_does_not_update_gender_when_gender_is_null(): void
    {
        User::factory()->create([
            'ext_id' => 155,
            'geschlecht' => 'd'
        ]);

        $data = $this->getData();
        $data['lehrer'][0]['id'] = 155;
        $data['lehrer'][0]['geschlecht'] = null;

        (new DataImportService($data))->execute();

        $this->assertDatabaseHas(self::TABLE, ['geschlecht' => 'd'])
            ->assertDatabaseMissing(self::TABLE, ['geschlecht' => ''])
            ->assertDatabaseCount(self::TABLE, 1);
    }

    /** Does not update gender when gender is empty */
    public function test_it_does_not_update_gender_when_gender_is_empty(): void
    {
        User::factory()->create([
            'ext_id' => 155,
            'geschlecht' => 'd'
        ]);

        $data = $this->getData();
        $data['lehrer'][0]['id'] = 155;
        $data['lehrer'][0]['geschlecht'] = '';

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, ['geschlecht' => 'd'])
            ->assertDatabaseMissing(self::TABLE, ['geschlecht' => '']);
    }

    /** It returns when the lehrer array is empty */
    public function test_it_returns_when_the_lehrer_array_is_missing(): void
    {
        $data = json_decode('{}', true);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /** It returns when the lehrer array is empty */
    public function test_it_returns_when_the_lehrer_array_is_empty(): void
    {
        $data = json_decode('{
            "lehrer": []
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }
}
