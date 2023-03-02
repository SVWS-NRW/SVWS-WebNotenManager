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

		$this->actingAs(user: $user)
			->getJson(uri: route(name: $this->url))
			->assertOk()
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

		$this->actingAs(user: $user)
			->getJson(uri: route(name: $this->url))
			->assertOk()
			->assertJsonCount(count: 0);
	}

	public function test_administrator_can_read_all_schueler(): void
	{
		Schueler::factory(count: 3)->create();

		$this->actingAs(user: User::factory()->administrator()->create())
			->getJson(uri: route(name: $this->url))
			->assertOk()
			->assertJsonCount(count: 3)
			->assertJsonStructure(structure: [
				'*' => [
					'id', 'nachname', 'vorname', 'name', 'geschlecht', 'klasse', 'ASV', 'AUE', 'ZB', 'gfs', 'gfsu',
				]
			]);
	}

	public function test_guests_cannot_read(): void
	{
		$this->getJson(uri: route(name: $this->url))
			->assertUnauthorized();
	}
}
