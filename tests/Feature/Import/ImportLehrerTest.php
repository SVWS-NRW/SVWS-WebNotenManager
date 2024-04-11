<?php

namespace Tests\Feature\Import;

use App\Models\User;
use App\Services\DataImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImportLehrerTest extends TestCase
{
    use RefreshDatabase;

    const TABLE = 'users';

    /**
     * Creates new Lehrer
     *
     * @return void
     */
    public function test_it_creates_lehrer(): void
    {
        $data = json_decode('{
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

        (new DataImportService($data))->execute();

        $this->assertDatabaseHas(self::TABLE, [
            'ext_id' => 155,
            'kuerzel' => 'ÖA',
            'nachname' => 'Ölschläger',
            'vorname' => 'Kevin',
            'geschlecht' => 'm',
            'email' => 'öa@svws-nrw.de'
        ])->assertDatabaseCount(self::TABLE, 1);
    }

    /**
     * Creates lehrer with random email when email is missing
     *
     * @return void
     */
    public function test_it_creates_lehrer_with_random_email_when_email_is_missing(): void
    {
        $data = json_decode('{
            "lehrer": [
                {
                    "id": 155,
                    "kuerzel": "ÖA",
                    "nachname": "Ölschläger",
                    "vorname": "Kevin",
                    "geschlecht": "m"
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseMissing(self::TABLE, [
            'eMailDienstlich' => ''
        ])->assertDatabaseCount(self::TABLE, 1);
    }

    /**
     * Creates lehrer with random email when email was not provided
     *
     * @return void
     */
    public function test_it_creates_lehrer_with_random_email_when_email_was_not_provided(): void
    {
        $data = json_decode('{
            "lehrer": [
                {
                    "id": 155,
                    "kuerzel": "ÖA",
                    "nachname": "Ölschläger",
                    "vorname": "Kevin",
                    "geschlecht": "m",
                    "eMailDienstlich": ""
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseMissing(self::TABLE, [
            'eMailDienstlich' => ''
        ])->assertDatabaseCount(self::TABLE, 1);
    }

    /**
     * Creates Lehrer with random email when invalid email provided
     *
     * @return void
     */
    public function test_it_creates_lehrer_with_random_email_when_invalid_email_provided(): void
    {
        $data = json_decode('{
            "lehrer": [
                {
                    "id": 155,
                    "kuerzel": "ÖA",
                    "nachname": "Ölschläger",
                    "vorname": "Kevin",
                    "geschlecht": "m",
                    "eMailDienstlich": "incorrect#email"
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseMissing(self::TABLE, [
            'eMailDienstlich' => 'incorrect#email'
        ])->assertDatabaseCount(self::TABLE, 1);
    }

    /**
     * Creates Lehrer with fallback gender if incorrect gender provided
     * Fallback gender is defined in \App\Models\User::class
     *
     * @return void
     */
    public function test_it_creates_lehrer_with_fallback_gender_if_incorrect_gender_provided(): void
    {
        $data = json_decode('{
            "lehrer": [
                {
                    "id": 155,
                    "kuerzel": "ÖA",
                    "nachname": "Ölschläger",
                    "vorname": "Kevin",
                    "geschlecht": "incorrect",
                    "eMailDienstlich": "öa@svws-nrw.de"
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseHas(self::TABLE, [
            'geschlecht' => User::FALLBACK_GENDER,
        ])->assertDatabaseMissing(self::TABLE, [
            'geschlecht' => 'incorrect'
        ])->assertDatabaseCount(self::TABLE, 1);
    }

    /**
     * Creates Lehrer with fallback gender if no gender provided
     * Fallback gender is defined in \App\Models\User::class
     *
     * @return void
     */
    public function test_it_creates_lehrer_with_fallback_gender_if_no_gender_provided(): void
    {
        $data = json_decode('{
            "lehrer": [
                {
                    "id": 155,
                    "kuerzel": "ÖA",
                    "nachname": "Ölschläger",
                    "vorname": "Kevin",
                    "eMailDienstlich": "öa@svws-nrw.de"
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseHas(self::TABLE, [
            'geschlecht' => User::FALLBACK_GENDER,
        ])->assertDatabaseMissing(self::TABLE, [
            'geschlecht' => ''
        ])->assertDatabaseCount(self::TABLE, 1);
    }

    /**
     * Updates details
     *
     * @return void
     */
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

        $data = json_decode('{
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

        (new DataImportService($data))->execute();

        $this->assertDatabaseHas(self::TABLE, [
            'ext_id' => 155,
            'kuerzel' => 'ÖA',
            'nachname' => 'Ölschläger',
            'vorname' => 'Kevin',
            'geschlecht' => 'm',
            'email' => 'öa@svws-nrw.de'
        ])->assertDatabaseMissing(self::TABLE, [
            'ext_id' => 155,
            'kuerzel' => 'MM',
            'nachname' => 'Max',
            'vorname' => 'Mustermann',
            'geschlecht' => 'd',
            'email' => 'mm@svws-nrw.de'
        ])->assertDatabaseCount(self::TABLE, 1);
    }

    /**
     * Does not update email when email has invalid format
     *
     * @return void
     */
    public function test_it_does_not_update_email_when_email_has_invalid_format(): void
    {
        User::factory()->create([
            'ext_id' => 155,
            'email' => 'öa@svws-nrw.de'
        ]);

        $data = json_decode('{
            "lehrer": [
                {
                    "id": 155,
                    "kuerzel": "ÖA",
                    "nachname": "Ölschläger",
                    "vorname": "Kevin",
                    "geschlecht": "m",
                    "eMailDienstlich": "invalid#email.com"
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseHas(self::TABLE, [
            'email' => 'öa@svws-nrw.de'
        ])->assertDatabaseMissing(self::TABLE, [
            'email' => 'invalid@email.com'
        ])->assertDatabaseCount(self::TABLE, 1);
    }

    /**
     * Does not update email when email is missing
     *
     * @return void
     */
    public function test_it_does_not_update_email_when_email_is_missing(): void
    {
        User::factory()->create([
            'ext_id' => 155,
            'email' => 'öa@svws-nrw.de'
        ]);

        $data = json_decode('{
            "lehrer": [
                {
                    "id": 155,
                    "kuerzel": "ÖA",
                    "nachname": "Ölschläger",
                    "vorname": "Kevin",
                    "geschlecht": "m"
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseHas(self::TABLE, [
            'email' => 'öa@svws-nrw.de'
        ])->assertDatabaseMissing(self::TABLE, [
            'email' => ''
        ])->assertDatabaseCount(self::TABLE, 1);
    }

    /**
     * Does not update gender when gender is invalid
     *
     * @return void
     */
    public function test_it_does_not_update_gender_when_gender_is_invalid(): void
    {
        User::factory()->create([
            'ext_id' => 155,
            'geschlecht' => 'd'
        ]);

        $data = json_decode('{
            "lehrer": [
                {
                    "id": 155,
                    "kuerzel": "ÖA",
                    "nachname": "Ölschläger",
                    "vorname": "Kevin",
                    "geschlecht": "Q",
                    "eMailDienstlich": "öa@svws-nrw.de"
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseHas(self::TABLE, [
            'geschlecht' => 'd'
        ])->assertDatabaseMissing(self::TABLE, [
            'geschlecht' => 'Q'
        ])->assertDatabaseCount(self::TABLE, 1);
    }

    /**
     * Does not update gender when gender is missing
     *
     * @return void
     */
    public function test_it_does_not_update_gender_when_gender_is_missing(): void
    {
        User::factory()->create([
            'ext_id' => 155,
            'geschlecht' => 'd'
        ]);

        $data = json_decode('{
            "lehrer": [
                {
                    "id": 155,
                    "kuerzel": "ÖA",
                    "nachname": "Ölschläger",
                    "vorname": "Kevin",
                    "eMailDienstlich": "öa@svws-nrw.de"
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseHas(self::TABLE, [
            'geschlecht' => 'd'
        ])->assertDatabaseMissing(self::TABLE, [
            'geschlecht' => ''
        ])->assertDatabaseCount(self::TABLE, 1);
    }

    /**
     * It returns when the lehrer array is empty
     *
     * @return void
     */
    public function test_it_returns_when_the_lehrer_array_is_missing(): void
    {
        $data = json_decode('{}', true);

        new DataImportService($data);

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    /**
     * It returns when the lehrer array is empty
     *
     * @return void
     */
    public function test_it_returns_when_the_lehrer_array_is_empty(): void
    {
        $data = json_decode('{
            "lehrer": []
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }
}
