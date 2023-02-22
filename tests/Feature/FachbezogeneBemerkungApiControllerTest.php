<?php

namespace Tests\Feature;

use App\Models\Leistung;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FachbezogeneBemerkungApiControllerTest extends TestCase
{
	use RefreshDatabase;

	private string $url = 'api.fachbezogene_bemerkung';

	private string $old = 'Lorem ipsum';
	private string $new = 'Dolor sit amet';

	public function test_users_can_update(): void
	{
		$this->actingAs(user: User::factory()->create());

		$leistung = Leistung::factory()->create(attributes: [
			'fachbezogeneBemerkungen' => $this->old,
		]);

		$response = $this->postJson(
			uri: route(name: $this->url, parameters: $leistung),
			data: ['bemerkung' => $this->new],
		);

		$response->assertNoContent();

		$this->assertDatabaseHas(table: 'leistungen', data: [
			'id' => $leistung->id,
			'fachbezogeneBemerkungen' => $this->new,
		])->assertDatabaseMissing(table: 'leistungen', data: [
			'id' => $leistung->id,
			'fachbezogeneBemerkungen' => $this->old,
		]);
	}

	public function test_guest_cannot_update(): void
	{
		$leistung = Leistung::factory()->create(attributes: ['fachbezogeneBemerkungen' => $this->old]);

		$response = $this->postJson(
			uri: route(name: $this->url, parameters: $leistung),
			data: ['bemerkung' => $this->new],
		);

		$response->assertUnauthorized();

		$this->assertDatabaseHas(table: 'leistungen', data: [
			'id' => $leistung->id,
			'fachbezogeneBemerkungen' => $this->old,
		])->assertDatabaseMissing(table: 'leistungen', data: [
			'id' => $leistung->id,
			'fachbezogeneBemerkungen' => $this->new,
		]);
	}

	public function test_ts_is_being_updated(): void
	{
		$this->actingAs(user: User::factory()->create());
		$timestamp = now()->subHour()->format(format: 'Y-m-d H:i:s.u');

		$leistung = Leistung::factory()->create(attributes: [
			'fachbezogeneBemerkungen' => $this->old,
			'tsFachbezogeneBemerkungen' => $timestamp,
		]);

		$response = $this->postJson(
			uri: route(name: $this->url, parameters: $leistung),
			data: ['bemerkung' => $this->new],
		);

		$response->assertNoContent();

		$this->assertDatabaseMissing(table: 'leistungen', data: [
			'id' => $leistung->id,
			'tsFachbezogeneBemerkungen' => $timestamp,
		]);
	}
}
