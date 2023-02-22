<?php

namespace Tests\Feature;

use App\Models\Floskel;
use App\Models\Floskelgruppe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FloskelnApiControllerTest extends TestCase
{
	use RefreshDatabase;

	private string $url = 'api.floskeln';

	public function test_users_can_read(): void
	{
		$this->actingAs(user: User::factory()->create());

		$floskelgruppe = Floskelgruppe::factory()->has(
			factory: Floskel::factory(3),
			relationship: 'floskeln',
		)->create();

		$response = $this->getJson(uri: route(name: $this->url, parameters: $floskelgruppe->kuerzel));

		$response->assertOk()
			->assertJsonCount(count: 3)
			->assertJsonStructure(structure: ['*' => ['id', 'kuerzel', 'text']]);
	}

	public function test_users_cannot_read_from_not_existing_floskelgruppe(): void
	{
		$this->actingAs(user: User::factory()->create());

		Floskelgruppe::factory()->has(
			factory: Floskel::factory(3),
			relationship: 'floskeln',
		)->create(attributes: ['kuerzel' => 'lorem-ipsum']);

		$response = $this->getJson(uri: route(name: $this->url, parameters: 'dolor-sit-amet'));

		$response->assertNotFound();
	}

	public function test_guest_cannot_read(): void
    {
		$floskelgruppe = Floskelgruppe::factory()->create();

		$response = $this->getJson(uri: route(name: $this->url, parameters: $floskelgruppe->kuerzel));

		$response->assertUnauthorized();
    }
}
