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

	private string $url = 'api.mein_unterricht';

	public function test_users_can_read_leistungen_of_schueler_from_common_lerngruppe(): void
	{
		$lerngruppe = Lerngruppe::factory()->create();

		$user = User::factory()->lehrer()->create();
		$user->lerngruppen()->attach(id: $lerngruppe);
		$this->actingAs(user: $user);
		Leistung::factory(3)->for(factory: $lerngruppe)->create();

		$response = $this->getJson(uri: route(name: $this->url));

		$response->assertOk()
			->assertJsonCount(count: 3)
			->assertJsonStructure(structure: [
				'*' => [
					'id', 'klasse', 'name', 'vorname', 'nachname', 'geschlecht', 'fach', 'fach_id', 'jahrgang',
					'lehrer', 'kurs', 'note', 'istGemahnt', 'mahndatum', 'fs', 'ufs', 'fachbezogeneBemerkungen',
				]
			])
		;
	}

	public function test_users_cannot_read_leistungen_of_schueler_from_common_lerngruppe(): void
	{
		$this->actingAs(user: User::factory()->lehrer()->create());

		Leistung::factory(5)->create();

		$response = $this->getJson(uri: route(name: $this->url));

		$response->assertOk()
			->assertJsonCount(count: 0);
	}

	public function test_guests_cannot_read(): void
	{
		Leistung::factory(5)->create();

		$response = $this->getJson(uri: route(name: $this->url));

		$response->assertUnauthorized();
	}
}
