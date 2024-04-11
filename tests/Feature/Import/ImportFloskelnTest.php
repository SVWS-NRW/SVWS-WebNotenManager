<?php

namespace Tests\Feature\Import;

use App\Models\{Jahrgang, Fach, Floskel};
use App\Services\DataImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImportFloskelnTest extends TestCase
{
    use RefreshDatabase;

    public const TABLE = 'floskeln';
/*
    public function test_it_does_not_create_related_floskeln_if_kuerzel_is_missing(): void
    {
        $data = json_decode('{
            "floskelgruppen": [
                {
                    "kuerzel": "ALLG",
                    "bezeichnung": "Allgemeine Floskeln",
                    "hauptgruppe": "ALLG",
                    "floskeln": [
                        {
                            "text": "$Vorname$ sollte sich aktiver am Unterrichtsgeschehen beteiligen.",
                            "fachID": null,
                            "niveau": null,
                            "jahrgangID": null
                        }
                    ]
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    public function test_it_does_not_create_related_floskeln_if_kuerzel_is_empty(): void
    {
        $data = json_decode('{
            "floskelgruppen": [
                {
                    "kuerzel": "ALLG",
                    "bezeichnung": "Allgemeine Floskeln",
                    "hauptgruppe": "ALLG",
                    "floskeln": [
                        {
                            "kuerzel": null,
                            "text": "$Vorname$ sollte sich aktiver am Unterrichtsgeschehen beteiligen.",
                            "fachID": null,
                            "niveau": null,
                            "jahrgangID": null
                        }
                    ]
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, [
                'kuerzel' => 'ALLG',
                'bezeichnung' => 'Allgemeine Floskeln',
                'hauptgruppe' => 'ALLG',
            ])
            ->assertDatabaseCount(self::TABLE, 0);
    }

    public function test_it_does_not_create_related_floskeln_if_kuerzel_already_exists(): void
    {
        Floskel::factory()->create(['kuerzel' => '#AU1']);

        $data = json_decode('{
            "floskelgruppen": [
                {
                    "kuerzel": "ALLG",
                    "bezeichnung": "Allgemeine Floskeln",
                    "hauptgruppe": "ALLG",
                    "floskeln": [
                        {
                            "kuerzel": "#AU1",
                            "text": "$Vorname$ sollte sich aktiver am Unterrichtsgeschehen beteiligen.",
                            "fachID": null,
                            "niveau": null,
                            "jahrgangID": null
                        }
                    ]
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, [
                'kuerzel' => '#AU1',
            ]);
    }

    public function test_it_creates_related_floskel_if_jahrgangId_is_null(): void
    {
        $data = json_decode('{
            "floskelgruppen": [
                {
                    "kuerzel": "ALLG",
                    "bezeichnung": "Allgemeine Floskeln",
                    "hauptgruppe": "ALLG",
                    "floskeln": [
                        {
                            "kuerzel": "#A1",
                            "text": "$Vorname$ sollte sich aktiver am Unterrichtsgeschehen beteiligen.",
                            "fachID": null,
                            "niveau": null,
                            "jahrgangID": null
                        }
                    ]
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, [
                'kuerzel' => '#A1',
            ]);
    }

    public function test_it_does_not_create_related_floskel_if_jahrgangId_is_missing(): void
    {
        $data = json_decode('{
            "floskelgruppen": [
                {
                    "kuerzel": "ALLG",
                    "bezeichnung": "Allgemeine Floskeln",
                    "hauptgruppe": "ALLG",
                    "floskeln": [
                        {
                            "kuerzel": "#A1",
                            "text": "$Vorname$ sollte sich aktiver am Unterrichtsgeschehen beteiligen.",
                            "fachID": null,
                            "niveau": null
                        }
                    ]
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    public function test_it_creates_related_floskel_if_fachId_is_null(): void
    {
        $data = json_decode('{
            "floskelgruppen": [
                {
                    "kuerzel": "ALLG",
                    "bezeichnung": "Allgemeine Floskeln",
                    "hauptgruppe": "ALLG",
                    "floskeln": [
                        {
                            "kuerzel": "#A1",
                            "text": "$Vorname$ sollte sich aktiver am Unterrichtsgeschehen beteiligen.",
                            "fachID": null,
                            "niveau": null,
                            "jahrgangID": null
                        }
                    ]
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, [
                'kuerzel' => '#A1',
            ]);
    }

    public function test_it_does_not_create_related_floskel_if_fachId_is_missing(): void
    {
        $data = json_decode('{
            "floskelgruppen": [
                {
                    "kuerzel": "ALLG",
                    "bezeichnung": "Allgemeine Floskeln",
                    "hauptgruppe": "ALLG",
                    "floskeln": [
                        {
                            "kuerzel": "#A1",
                            "text": "$Vorname$ sollte sich aktiver am Unterrichtsgeschehen beteiligen.",
                            "niveau": null,
                            "jahrgangID": null
                        }
                    ]
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    public function test_it_does_not_create_related_floskel_with_related_fach_if_fach_does_not_exist(): void
    {
        $data = json_decode('{
            "floskelgruppen": [
                {
                    "kuerzel": "ALLG",
                    "bezeichnung": "Allgemeine Floskeln",
                    "hauptgruppe": "ALLG",
                    "floskeln": [
                        {
                            "kuerzel": "#A1",
                            "text": "$Vorname$ sollte sich aktiver am Unterrichtsgeschehen beteiligen.",
                            "fachID": 1,
                            "niveau": null,
                            "jahrgangID": null
                        }
                    ]
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    public function test_it_creates_related_floskel_with_related_fach_if_fach_exists(): void
    {
        Fach::factory()->create();

        $data = json_decode('{
            "floskelgruppen": [
                {
                    "kuerzel": "ALLG",
                    "bezeichnung": "Allgemeine Floskeln",
                    "hauptgruppe": "ALLG",
                    "floskeln": [
                        {
                            "kuerzel": "#A1",
                            "text": "$Vorname$ sollte sich aktiver am Unterrichtsgeschehen beteiligen.",
                            "fachID": 1,
                            "niveau": null,
                            "jahrgangID": null
                        }
                    ]
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, [
                'kuerzel' => '#A1',
                'fach_id' => 1,
            ]);
    }
    public function test_it_does_not_create_related_floskel_with_related_jahrgang_if_jahrgang_does_not_exist(): void
    {
        $data = json_decode('{
            "floskelgruppen": [
                {
                    "kuerzel": "ALLG",
                    "bezeichnung": "Allgemeine Floskeln",
                    "hauptgruppe": "ALLG",
                    "floskeln": [
                        {
                            "kuerzel": "#A1",
                            "text": "$Vorname$ sollte sich aktiver am Unterrichtsgeschehen beteiligen.",
                            "fachID": null,
                            "niveau": null,
                            "jahrgangID": 1
                        }
                    ]
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 0);
    }

    public function test_it_creates_related_floskel_with_related_jahrgang_if_jahrgang_exists(): void
    {
        Jahrgang::factory()->create();

        $data = json_decode('{
            "floskelgruppen": [
                {
                    "kuerzel": "ALLG",
                    "bezeichnung": "Allgemeine Floskeln",
                    "hauptgruppe": "ALLG",
                    "floskeln": [
                        {
                            "kuerzel": "#A1",
                            "text": "$Vorname$ sollte sich aktiver am Unterrichtsgeschehen beteiligen.",
                            "fachID": null,
                            "niveau": null,
                            "jahrgangID": 1
                        }
                    ]
                }
            ]
        }', true);

        (new DataImportService($data))->execute();

        $this->assertDatabaseCount(self::TABLE, 1)
            ->assertDatabaseHas(self::TABLE, [
                'kuerzel' => '#A1',
                'jahrgang_id' => 1,
            ]);
    }

    */
}
