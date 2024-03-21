<?php

namespace Tests\Feature\Import;

use App\Models\User;
use App\Services\DataImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImportLehrerTest extends TestCase
{
    use RefreshDatabase;

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

        new DataImportService($data);

        $this->assertDatabaseHas('users', [
            'ext_id' => 155,
            'kuerzel' => 'ÖA',
            'nachname' => 'Ölschläger',
            'vorname' => 'Kevin',
            'geschlecht' => 'm',
            'email' => 'öa@svws-nrw.de'
        ])->assertDatabaseCount('users', 1);
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

        new DataImportService($data);

        $this->assertDatabaseMissing('users', [
            'eMailDienstlich' => ''
        ])->assertDatabaseCount('users', 1);
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

        new DataImportService($data);

        $this->assertDatabaseMissing('users', [
            'eMailDienstlich' => ''
        ])->assertDatabaseCount('users', 1);
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

        new DataImportService($data);

        $this->assertDatabaseMissing('users', [
            'eMailDienstlich' => 'incorrect#email'
        ])->assertDatabaseCount('users', 1);
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

        new DataImportService($data);

        $this->assertDatabaseHas('users', [
            'geschlecht' => User::FALLBACK_GENDER,
        ])->assertDatabaseMissing('users', [
            'geschlecht' => 'incorrect'
        ])->assertDatabaseCount('users', 1);
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

        new DataImportService($data);

        $this->assertDatabaseHas('users', [
            'geschlecht' => User::FALLBACK_GENDER,
        ])->assertDatabaseMissing('users', [
            'geschlecht' => ''
        ])->assertDatabaseCount('users', 1);
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

        new DataImportService($data);

        $this->assertDatabaseHas('users', [
            'ext_id' => 155,
            'kuerzel' => 'ÖA',
            'nachname' => 'Ölschläger',
            'vorname' => 'Kevin',
            'geschlecht' => 'm',
            'email' => 'öa@svws-nrw.de'
        ])->assertDatabaseMissing('users', [
            'ext_id' => 155,
            'kuerzel' => 'MM',
            'nachname' => 'Max',
            'vorname' => 'Mustermann',
            'geschlecht' => 'd',
            'email' => 'mm@svws-nrw.de'
        ])->assertDatabaseCount('users', 1);
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

        new DataImportService($data);

        $this->assertDatabaseHas('users', [
            'email' => 'öa@svws-nrw.de'
        ])->assertDatabaseMissing('users', [
            'email' => 'invalid@email.com'
        ])->assertDatabaseCount('users', 1);
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

        new DataImportService($data);

        $this->assertDatabaseHas('users', [
            'email' => 'öa@svws-nrw.de'
        ])->assertDatabaseMissing('users', [
            'email' => ''
        ])->assertDatabaseCount('users', 1);
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

        new DataImportService($data);

        $this->assertDatabaseHas('users', [
            'geschlecht' => 'd'
        ])->assertDatabaseMissing('users', [
            'geschlecht' => 'Q'
        ])->assertDatabaseCount('users', 1);
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

        new DataImportService($data);

        $this->assertDatabaseHas('users', [
            'geschlecht' => 'd'
        ])->assertDatabaseMissing('users', [
            'geschlecht' => ''
        ])->assertDatabaseCount('users', 1);
    }
}
