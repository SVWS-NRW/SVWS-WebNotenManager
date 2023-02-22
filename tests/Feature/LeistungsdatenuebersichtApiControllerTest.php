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

	private string $url = 'api.leistungsdatenuebersicht';

	public function test_users_can_read_leistungen_of_schueler_for_common_klasse(): void
	{
		$klasse = Klasse::factory()->create();
		$schueler = Schueler::factory(count: 3)->for(factory: $klasse)->create();

		$user = User::factory()->lehrer()->create();
		$user->klassen()->attach(id: $klasse);
		$this->actingAs(user: $user);

		foreach ($schueler as $item) {
			Leistung::factory()->for(factory: $item)->create();
		}

		$response = $this->getJson(uri: route(name: $this->url));

		$response->assertOk()
			->assertJsonCount(count: 3)
			->assertJsonStructure(structure: [
				'*' => [
					'id', 'klasse', 'name', 'vorname', 'nachname', 'geschlecht', 'fach', 'fach_id', 'jahrgang', 'lehrer',
					'kurs', 'note', 'istGemahnt', 'mahndatum', 'fs', 'ufs', 'fachbezogeneBemerkungen',
				]
			]);
	}

	public function test_users_cannot_read_leistungen_of_schueler_from_not_common_klasse(): void
	{
		$klasse = Klasse::factory()->create();
		$user = User::factory()->lehrer()->create();
		$user->klassen()->attach(id: $klasse);
		$this->actingAs(user: $user);

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
