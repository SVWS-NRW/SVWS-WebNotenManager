<?php

namespace Tests\Feature;

use App\Models\Klasse;
use App\Models\Schueler;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KlassenleitungApiControllerTest extends TestCase
{
	use RefreshDatabase;

	private string $url = 'api.klassenleitung';

	public function test_user_can_read_schueler_from_common_class(): void
	{
		$klasse = Klasse::factory()->create();
		Schueler::factory(count: 3)->for(factory: $klasse)->create();

		$user = User::factory()->lehrer()->create();
		$user->klassen()->attach(id: $klasse);
		$this->actingAs(user: $user);

		$response = $this->getJson(uri: route(name: $this->url));

		$response->assertOk()
			->assertJsonCount(count: 3)
			->assertJsonStructure(structure: [
				'*' => [
					'id', 'nachname', 'vorname', 'name', 'geschlecht', 'klasse', 'ASV', 'AUE', 'ZB', 'gfs', 'gfsu',
				]
			]);
	}

	public function test_user_cannot_read_schueler_from_not_common_class(): void
	{
		Schueler::factory(count: 3)->create();

		$klasse = Klasse::factory()->create();
		$user = User::factory()->lehrer()->create();
		$user->klassen()->attach(id: $klasse);
		$this->actingAs(user: $user);

		$response = $this->getJson(uri: route(name: $this->url));

		$response->assertOk()
			->assertJsonCount(count: 0);
	}

	public function test_administrator_cannot_read(): void
	{
		$this->actingAs(user: User::factory()->administrator()->create());

		$response = $this->getJson(uri: route(name: $this->url));

		$response->assertForbidden();
	}

	public function test_guests_cannot_read(): void
	{
		$response = $this->getJson(uri: route(name: $this->url));

		$response->assertUnauthorized();
	}
}
