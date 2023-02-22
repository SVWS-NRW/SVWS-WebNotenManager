<?php

namespace Tests\Feature;

use App\Models\Fach;
use App\Models\Floskel;
use App\Models\Floskelgruppe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FachbezogeneFloskelnApiControllerTest extends TestCase
{
	use RefreshDatabase;

	private string $url = 'api.fachbezogene_floskeln';

	public function test_users_can_read(): void
	{
		$this->actingAs(user: User::factory()->create());

		$fach = Fach::factory()->create();
		$floskelgruppe = Floskelgruppe::factory()->create(attributes: ['kuerzel' => 'FACH']);
		Floskel::factory(3)->for(factory: $floskelgruppe)->for(factory: $fach)->niveau()->jahrgang()->create();

		$response = $this->getJson(uri: route(name: $this->url, parameters: $fach));

		$response->assertOk()
			->assertJsonCount(count: 3, key: 'data')
			->assertJsonCount(count: 3, key: 'niveau')
			->assertJsonCount(count: 3, key: 'jahrgaenge')
			->assertJsonStructure(structure: [
				'data' => [
					'*' => ['kuerzel', 'text', 'niveau', 'jahrgang'],
				],
				'niveau',
				'jahrgaenge',
			]);
	}

	public function test_guests_cannot_read(): void
	{
		$fach = Fach::factory()->create();
		$floskelgruppe = Floskelgruppe::factory()->create(attributes: ['kuerzel' => 'FACH']);
		Floskel::factory(3)->for(factory: $floskelgruppe)->for(factory: $fach)->niveau()->jahrgang()->create();

		$response = $this->getJson(uri: route(name: $this->url, parameters: $fach));

		$response->assertUnauthorized();
	}
}
