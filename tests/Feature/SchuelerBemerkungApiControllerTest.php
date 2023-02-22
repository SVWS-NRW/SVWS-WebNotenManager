<?php

namespace Tests\Feature;

use App\Models\Bemerkung;
use App\Models\Schueler;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SchuelerBemerkungApiControllerTest extends TestCase
{
	use RefreshDatabase;

	private string $url = 'api.schueler_bemerkung';
	private string $old = 'Lorem ipsum';
	private string $new = 'Dolor sit amet';
	private string $firstAllowedColumn = Bemerkung::ALLOWED_BEMERKUNGEN[0];

	public function test_users_can_update(): void
	{
		foreach (Bemerkung::ALLOWED_BEMERKUNGEN as $key) {
			$schueler = Schueler::factory()->create();
			$bemerkung = Bemerkung::factory()->for(factory: $schueler)->create(attributes: [$key => $this->old]);

			$user = User::factory()->create();
			$user->klassen()->attach($schueler->klasse_id);
			$this->actingAs(user: $user);

			$response = $this->postJson(
				uri: route(name: $this->url, parameters: $schueler),
				data: ['key' => $key, 'value' => $this->new]
			);

			$response->assertNoContent();

			$this->assertDatabaseHas(table: 'bemerkungen', data: ['id' => $bemerkung->id, $key => $this->new])
				->assertDatabaseMissing(table: 'bemerkungen', data: ['id' => $bemerkung->id, $key => $this->old]);
		}
	}

	public function test_administrators_cannot_update(): void
	{
		$schueler = Schueler::factory()->create();

		$bemerkung = Bemerkung::factory()
			->for(factory: $schueler)
			->create(attributes: [$this->firstAllowedColumn => $this->old]);

		$this->actingAs(user: User::factory()->administrator()->create());

		$response = $this->postJson(
			uri: route(name: $this->url, parameters: $schueler),
			data: ['key' => $this->firstAllowedColumn, 'value' => $this->new]
		);

		$response->assertForbidden();

		$this->assertDatabaseHas(
			table: 'bemerkungen',
			data: ['id' => $bemerkung->id, $this->firstAllowedColumn => $this->old]
		)->assertDatabaseMissing(
			table: 'bemerkungen',
			data: ['id' => $bemerkung->id, $this->firstAllowedColumn => $this->new]
		);
	}

	public function test_guest_cannot_update(): void
	{
		$schueler = Schueler::factory()->create();

		$bemerkung = Bemerkung::factory()
			->for(factory: $schueler)
			->create(attributes: [$this->firstAllowedColumn => $this->old]);

		$response = $this->postJson(
			uri: route(name: $this->url, parameters: $schueler),
			data: ['key' => $this->firstAllowedColumn, 'value' => $this->new],
		);

		$response->assertUnauthorized();

		$this->assertDatabaseHas(
			table: 'bemerkungen',
			data: ['id' => $bemerkung->id, $this->firstAllowedColumn => $this->old],
		)->assertDatabaseMissing(
			table: 'bemerkungen',
			data: ['id' => $bemerkung->id, $this->firstAllowedColumn => $this->new],
		);
	}

	public function test_timestamp_is_updated(): void
	{
		foreach (Bemerkung::ALLOWED_BEMERKUNGEN as $key) {
			$timestamp = now()->subHour()->format(format: 'Y-m-d H:i:s.u');
			$schueler = Schueler::factory()->create();
			$bemerkung = Bemerkung::factory()->for(factory: $schueler)->create(attributes: [
				$key => $this->old,
				"ts{$key}" => $timestamp,
			]);

			$user = User::factory()->create();
			$user->klassen()->attach($schueler->klasse_id);
			$this->actingAs(user: $user);

			$response = $this->postJson(
				uri: route(name: $this->url, parameters: $schueler),
				data: ['key' => $key, 'value' => $this->new]
			);

			$response->assertNoContent();

			$this->assertDatabaseMissing(table: 'bemerkungen', data: ['id' => $bemerkung->id, "ts{$key}" => $timestamp]);
		}
	}

	public function test_users_cannot_update_bemerkungen_of_a_schuler_not_in_their_own_class(): void
	{
		$schueler = Schueler::factory()->create();
		$bemerkung = Bemerkung::factory()
			->for(factory: $schueler)
			->create(attributes: [$this->firstAllowedColumn => $this->old]);

		$this->actingAs(user: User::factory()->create());

		$response = $this->postJson(
			uri: route(name: $this->url, parameters: $schueler),
			data: ['key' => $this->firstAllowedColumn, 'value' => $this->new]
		);

		$response->assertForbidden();

		$this->assertDatabaseHas(
			table: 'bemerkungen',
			data: ['id' => $bemerkung->id, $this->firstAllowedColumn => $this->old],
		)->assertDatabaseMissing(
			table: 'bemerkungen',
			data: ['id' => $bemerkung->id, $this->firstAllowedColumn => $this->new],
		);
	}

	public function test_users_cannot_update_bemerkungen_that_are_not_in_the_allowed_list(): void
	{
		$key = 'randomInvalidKey' . time();

		$schueler = Schueler::factory()->has(
			factory: Bemerkung::factory()
		)->create();

		$user = User::factory()->create();
		$user->klassen()->attach($schueler->klasse_id);

		$this->actingAs(user: $user);

		$response = $this->postJson(
			uri: route(name: $this->url, parameters: $schueler),
			data: ['key' => $key, 'value' => $this->new]
		);

		$response->assertUnprocessable();
	}
}
