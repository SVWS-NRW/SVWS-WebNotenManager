<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingsApiControllerTest extends TestCase
{
	use RefreshDatabase;

	private string $indexUrl = 'api.settings.index';
	private string $updateUrl = 'api.settings.update';

	public function test_administrator_can_list(): void
	{
		$this->actingAs(user: User::factory()->administrator()->create());

		$type = 'randomString';

		Setting::factory()->count(count: 3)->create(['type' => $type]);

		$response = $this->getJson(uri: route(name: $this->indexUrl, parameters: ['type' => $type]));

		$response->assertOk()
			->assertJsonCount(count: 3)
			->assertJsonStructure(['*' => ['key', 'value']]);
	}

	public function test_lehrer_cannot_list(): void
	{
		$this->actingAs(user: User::factory()->lehrer()->create());

		$response = $this->getJson(uri: route(name: $this->indexUrl, parameters: ['type' => 'randomString']));

		$response->assertForbidden();
	}

	public function test_guest_cannot_list(): void
	{
		$response = $this->getJson(uri: route(name: $this->indexUrl, parameters: ['type' => 'randomString']));

		$response->assertUnauthorized();
	}

	public function test_administrator_can_update(): void
	{
		$this->actingAs(user: User::factory()->administrator()->create());

		Setting::factory()->create(['type' => 'myType', 'key' => 'myKey', 'value' => 'oldValue']);

		$response = $this->putJson(
			uri: route(name: $this->updateUrl),
			data: ['type' => 'myType', 'settings' => ['myKey' => 'newValue']]
		);

		$response->assertNoContent();

		$this->assertDatabaseHas(
			table: 'settings',
			data: ['type' => 'myType', 'key' => 'myKey', 'value' => 'newValue']
		)->assertDatabaseMissing(
			table: 'settings',
			data: ['type' => 'myType', 'key' => 'myKey', 'value' => 'oldValue'],
		);
	}

	public function test_lehrer_cannot_update(): void
	{
		$this->actingAs(user: User::factory()->lehrer()->create());

		$response = $this->putJson(uri: route(name: $this->updateUrl));

		$response->assertForbidden();
	}

	public function test_guest_cannot_update(): void
	{
		$response = $this->putJson(uri: route(name: $this->updateUrl));

		$response->assertUnauthorized();
	}
}
