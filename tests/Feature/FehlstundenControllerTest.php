<?php

namespace Tests\Feature;

use App\Models\Leistung;
use App\Models\Lerngruppe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FehlstundenControllerTest extends TestCase
{
	use RefreshDatabase;

	private string $urlFehlstundenGesamt = 'api.fehlstunden.leistung.gesamt';
	private string $urlFehlstundenUnentschuldigt = 'api.fehlstunden.leistung.unentschuldigt';

	public function test_user_can_set_fehlstunden_gesamt(): void
	{
		$lerngruppe = Lerngruppe::factory()->create();

		$user = User::factory()->lehrer()->create();
		$user->lerngruppen()->attach(id: $lerngruppe);

		$leistung = Leistung::factory()
			->for(factory: $lerngruppe)
			->create(attributes: [
				'fehlstundenGesamt' => 3,
				'fehlstundenUnentschuldigt' => 3,
			]);

		$this->actingAs(user: $user)->postJson(
			uri: route(name: $this->urlFehlstundenGesamt, parameters: $leistung),
			data: ['value' => 5]
		)->assertNoContent();

		$this->assertDatabaseHas(
			table: 'leistungen', data: ['id' => $leistung->id, 'fehlstundenGesamt' => 5]
		)->assertDatabaseMissing(
			table: 'leistungen', data: ['id' => $leistung->id, 'fehlstundenGesamt' => 3]
		);
	}

	public function test_user_can_set_fehlstunden_unentschuldigt(): void
	{
		$lerngruppe = Lerngruppe::factory()->create();

		$user = User::factory()->lehrer()->create();
		$user->lerngruppen()->attach(id: $lerngruppe);

		$leistung = Leistung::factory()
			->for(factory: $lerngruppe)
			->create(attributes: [
				'fehlstundenGesamt' => 5,
				'fehlstundenUnentschuldigt' => 3,
			]);

		$this->actingAs(user: $user)->postJson(
			uri: route(name: $this->urlFehlstundenUnentschuldigt, parameters: $leistung),
			data: ['value' => 5]
		)->assertNoContent();

		$this->assertDatabaseHas(
			table: 'leistungen', data: ['id' => $leistung->id, 'fehlstundenUnentschuldigt' => 5]
		)->assertDatabaseMissing(
			table: 'leistungen', data: ['id' => $leistung->id, 'fehlstundenUnentschuldigt' => 3]
		);
	}

	public function test_user_cannot_set_fehlstunden_gesamt_smaller_than_fehlstunden_unentschuldigt(): void
	{
		$lerngruppe = Lerngruppe::factory()->create();

		$user = User::factory()->lehrer()->create();
		$user->lerngruppen()->attach(id: $lerngruppe);

		$leistung = Leistung::factory()->for(factory: $lerngruppe)->create(attributes: [
			'fehlstundenGesamt' => 3,
			'fehlstundenUnentschuldigt' => 3,
		]);

		$this->actingAs(user: $user)->postJson(
			uri: route(name: $this->urlFehlstundenGesamt, parameters: $leistung),
			data: ['value' => 1]
		)->assertUnprocessable();

		$this->assertDatabaseHas(
			table: 'leistungen', data: ['id' => $leistung->id, 'fehlstundenGesamt' => 3]
		)->assertDatabaseMissing(
			table: 'leistungen', data: ['id' => $leistung->id, 'fehlstundenGesamt' => 1]
		);
	}


	public function test_user_cannot_set_fehlstunden_unentschuldigt_greater_than_fehlstunden_gesamt(): void
	{
		$lerngruppe = Lerngruppe::factory()->create();

		$user = User::factory()->lehrer()->create();
		$user->lerngruppen()->attach(id: $lerngruppe);

		$leistung = Leistung::factory()->for(factory: $lerngruppe)->create(attributes: [
			'fehlstundenGesamt' => 3,
			'fehlstundenUnentschuldigt' => 3,
		]);

		$this->actingAs(user: $user)->postJson(
			uri: route(name: $this->urlFehlstundenUnentschuldigt, parameters: $leistung),
			data: ['value' => 5]
		)->assertUnprocessable();

		$this->assertDatabaseHas(
			table: 'leistungen', data: ['id' => $leistung->id, 'fehlstundenUnentschuldigt' => 3]
		)->assertDatabaseMissing(
			table: 'leistungen', data: ['id' => $leistung->id, 'fehlstundenUnentschuldigt' => 5]
		);
	}

	public function test_user_cannot_set_fehlstunden_gesamt_for_leistung_not_in_a_common_lerngruppe(): void
	{
		$leistung = Leistung::factory()->create(attributes: [
			'fehlstundenGesamt' => 3,
		]);

		$this->actingAs(user: User::factory()->lehrer()->create())
			->postJson(
				uri: route(name: $this->urlFehlstundenGesamt, parameters: $leistung),
				data: ['value' => 5]
			)->assertForbidden();

		$this->assertDatabaseHas(
			table: 'leistungen', data: ['id' => $leistung->id, 'fehlstundenGesamt' => 3]
		)->assertDatabaseMissing(
			table: 'leistungen', data: ['id' => $leistung->id, 'fehlstundenGesamt' => 5]
		);
	}

	public function test_user_cannot_set_fehlstunden_unentschuldigt_for_leistung_not_in_a_common_lerngruppe(): void
	{
		$leistung = Leistung::factory()->create(attributes: [
			'fehlstundenUnentschuldigt' => 3,
		]);

		$this->actingAs(user: User::factory()->lehrer()->create())->postJson(
			uri: route(name: $this->urlFehlstundenUnentschuldigt, parameters: $leistung),
			data: ['value' => 5]
		)->assertForbidden();

		$this->assertDatabaseHas(
			table: 'leistungen', data: ['id' => $leistung->id, 'fehlstundenUnentschuldigt' => 3]
		)->assertDatabaseMissing(
			table: 'leistungen', data: ['id' => $leistung->id, 'fehlstundenUnentschuldigt' => 5]
		);
	}

	public function test_guest_cannot_set_fehlstunden_gesamt(): void
	{
		$this->postJson(
			uri: route(name: $this->urlFehlstundenGesamt, parameters: Leistung::factory()->create()),
			data: ['value' => 5]
		)->assertUnauthorized();
	}

	public function test_guest_cannot_set_fehlstunden_unentschuldigt(): void
	{
		$this->postJson(
			uri: route(name: $this->urlFehlstundenUnentschuldigt, parameters: Leistung::factory()->create()),
			data: ['value' => 5]
		)->assertUnauthorized();
	}
}
