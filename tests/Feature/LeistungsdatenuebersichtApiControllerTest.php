<?php

namespace Tests\Feature;

use App\Models\Klasse;
use App\Models\Leistung;
use App\Models\Schueler;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeistungsdatenuebersichtApiControllerTest extends TestCase
{
	use RefreshDatabase;

    /**
     * Endpoint url
     *
     * @var string
     */
	private string $url = 'api.leistungsdatenuebersicht';

    /**
     * Test if users can read leistungen of schueler for common klasse
     *
     * @return void
     */
    public function test_users_can_read_leistungen_of_schueler_for_common_klasse(): void
	{
		$klasse = Klasse::factory()->create();
		$schueler = Schueler::factory(3)->for($klasse)->create();

		$user = User::factory()->lehrer()->create();
		$user->klassen()->attach($klasse);

		foreach ($schueler as $item) {
			Leistung::factory()->for($item)->create();
		}

		$this->actingAs($user)
            ->getJson(route($this->url))
            ->assertOk()
			->assertJsonCount(3)
			->assertJsonStructure([
				'*' => [
					'id', 'klasse', 'name', 'vorname', 'nachname', 'geschlecht', 'fach', 'fach_id', 'jahrgang', 'lehrer',
					'kurs', 'note', 'istGemahnt', 'mahndatum', 'fs', 'ufs', 'fachbezogeneBemerkungen',
				]
			]);
	}

    /**
     * Test if users cannot read leistungen of schueler from not common klasse
     *
     * @return void
     */
	public function test_users_cannot_read_leistungen_of_schueler_from_not_common_klasse(): void
	{
		$klasse = Klasse::factory()->create();
		$user = User::factory()->lehrer()->create();
		$user->klassen()->attach($klasse);

		Leistung::factory(5)->create();

		$this->actingAs($user)
            ->getJson(route($this->url))
            ->assertOk()
			->assertJsonCount(0);
	}

    /**
     * Test if users guests cannot read
     *
     * @return void
     */
	public function test_guests_cannot_read(): void
	{
		Leistung::factory(5)->create();

		$this->getJson(route($this->url))
            ->assertUnauthorized();
	}
}
