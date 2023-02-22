<?php

namespace Tests\Feature;

use App\Models\Leistung;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MahnungenApiControllerTest extends TestCase
{
	use RefreshDatabase;

	private string $url = 'api.mahnung';

    public function test_users_can_set(): void
    {
		$this->actingAs(user: User::factory()->create());

		$leistung = Leistung::factory()->create(attributes: ['istGemahnt' => false]);

		$response = $this->postJson(uri: route(name: $this->url, parameters: $leistung), data: ['istGemahnt' => true]);

		$response->assertNoContent();

		$this->assertDatabaseHas(table: 'leistungen', data: ['id' => $leistung->id, 'istGemahnt' => true])
			->assertDatabaseMissing(table: 'leistungen', data: ['id' => $leistung->id, 'istGemahnt' => false]);
    }

	public function test_users_can_unset(): void
	{
		$this->actingAs(user: User::factory()->create());

		$leistung = Leistung::factory()->create(attributes: ['istGemahnt' => true]);

		$response = $this->postJson(uri: route(name: $this->url, parameters: $leistung), data: ['istGemahnt' => false]);

		$response->assertNoContent();

		$this->assertDatabaseHas(table: 'leistungen', data: ['id' => $leistung->id, 'istGemahnt' => false])
			->assertDatabaseMissing(table: 'leistungen', data: ['id' => $leistung->id, 'istGemahnt' => true]);
	}

	public function test_guest_cannot_update(): void
	{
		$leistung = Leistung::factory()->create(attributes: ['istGemahnt' => false]);

		$response = $this->postJson(uri: route(name: $this->url, parameters: $leistung), data: ['istGemahnt' => true]);

		$response->assertUnauthorized();

		$this->assertDatabaseHas(table: 'leistungen', data: ['id' => $leistung->id, 'istGemahnt' => false])
			->assertDatabaseMissing(table: 'leistungen', data: ['id' => $leistung->id, 'fachbezogeneBemerkungen' => true]);
	}

	public function test_ts_is_being_updated(): void
	{
		$this->actingAs(user: User::factory()->create());
		$timestamp = now()->subHour()->format(format: 'Y-m-d H:i:s.u');

		$leistung = Leistung::factory()->create(attributes: ['istGemahnt' => false, 'tsIstGemahnt' => $timestamp]);

		$response = $this->postJson(uri: route(name: $this->url, parameters: $leistung), data: ['istGemahnt' => true]);

		$response->assertNoContent();

		$this->assertDatabaseMissing(table: 'leistungen', data: ['id' => $leistung->id, 'tsIstGemahnt' => $timestamp]);
	}
}
