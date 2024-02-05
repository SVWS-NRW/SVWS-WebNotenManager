<?php

namespace Tests\Feature;

use App\Models\Leistung;
use App\Models\Lerngruppe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MeinUnterrichtApiControllerTest extends TestCase
{
	use RefreshDatabase;

    /**
     * Endpoint url
     *
     * @var string
     */
	private string $url = 'api.mein_unterricht';

    /**
     * Test if users can read Leistungen of schueler from common lerngruppe
     *
     * @return void
     */
	public function test_users_can_read_leistungen_of_schueler_from_common_lerngruppe(): void
	{
		$lerngruppe = Lerngruppe::factory()->create();
		$user = User::factory()->lehrer()->create();
		$user->lerngruppen()->attach($lerngruppe);
		Leistung::factory(3)->for($lerngruppe)->create();

		$this->actingAs($user)
            ->getJson(route($this->url))
            ->assertOk()
			->assertJsonCount(3)
			->assertJsonStructure([
				'*' => [
					'id', 'klasse', 'name', 'vorname', 'nachname', 'geschlecht', 'fach', 'fach_id', 'jahrgang',
					'lehrer', 'kurs', 'note', 'istGemahnt', 'mahndatum', 'fs', 'ufs', 'fachbezogeneBemerkungen',
				]
			])
		;
	}

    /**
     * Test if users cannot read leistungen of schueler from common lerngruppe
     *
     * @return void
     */
	public function test_users_cannot_read_leistungen_of_schueler_from_common_lerngruppe(): void
	{
		Leistung::factory(5)->create();

		$this->actingAs(User::factory()->lehrer()->create())
            ->getJson(route($this->url))
            ->assertOk()
			->assertJsonCount(0);
	}

    /**
     * Test if guest cannot read contents of Mein Unterricht
     *
     * @return void
     */
	public function test_guests_cannot_read(): void
	{
		Leistung::factory(5)->create();

		$this->getJson(route($this->url))->assertUnauthorized();
	}
}
