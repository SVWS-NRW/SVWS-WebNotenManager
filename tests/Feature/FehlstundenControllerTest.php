<?php

namespace Tests\Feature;

use App\Models\Klasse;
use App\Models\Leistung;
use App\Models\Lernabschnitt;
use App\Models\Lerngruppe;
use App\Models\Schueler;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FehlstundenControllerTest extends TestCase
{
	use RefreshDatabase;

	private string $urlLeistungFehlstundenGesamt = 'api.fehlstunden.leistung.gesamt';
	private string $urlLeistungFehlstundenUnentschuldigt = 'api.fehlstunden.leistung.unentschuldigt';
	private string $urlSchuelerFehlstundenGesamt = 'api.fehlstunden.schueler.gesamt';
	private string $urlSchuelerFehlstundenGesamtUnentschuldigt = 'api.fehlstunden.schueler.gesamt_unentschuldigt';

	public function test_user_can_set_leistung_fehlstunden_gesamt(): void
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
			uri: route(name: $this->urlLeistungFehlstundenGesamt, parameters: $leistung),
			data: ['value' => 5]
		)->assertNoContent();

		$this->assertDatabaseHas(
			table: 'leistungen', data: ['id' => $leistung->id, 'fehlstundenGesamt' => 5]
		)->assertDatabaseMissing(
			table: 'leistungen', data: ['id' => $leistung->id, 'fehlstundenGesamt' => 3]
		);
	}

	public function test_user_can_set_leistung_fehlstunden_unentschuldigt(): void
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
			uri: route(name: $this->urlLeistungFehlstundenUnentschuldigt, parameters: $leistung),
			data: ['value' => 5]
		)->assertNoContent();

		$this->assertDatabaseHas(
			table: 'leistungen', data: ['id' => $leistung->id, 'fehlstundenUnentschuldigt' => 5]
		)->assertDatabaseMissing(
			table: 'leistungen', data: ['id' => $leistung->id, 'fehlstundenUnentschuldigt' => 3]
		);
	}


	public function test_user_can_set_schueler_fehlstunden_gesamt(): void
	{
		$klasse = Klasse::factory()->create();

		$user = User::factory()->lehrer()->create();
		$user->klassen()->attach(id: $klasse);

		$schueler = Schueler::factory()->for(factory: $klasse)->create();

		Lernabschnitt::factory()
			->for(factory: $schueler)
			->create(attributes: [
				'fehlstundenGesamt' => 3,
				'fehlstundenGesamtUnentschuldigt' => 3,
			]);

		$this->actingAs(user: $user)->postJson(
			uri: route(name: $this->urlSchuelerFehlstundenGesamt, parameters: $schueler),
			data: ['value' => 5]
		)->assertNoContent();

		$this->assertDatabaseHas(
			table: 'lernabschnitte', data: ['schueler_id' => $schueler->id, 'fehlstundenGesamt' => 5]
		)->assertDatabaseMissing(
			table: 'lernabschnitte', data: ['schueler_id' => $schueler->id, 'fehlstundenGesamt' => 3]
		);
	}

	public function test_user_can_set_schueler_fehlstunden_gesamt_unentschuldigt(): void
	{
		$klasse = Klasse::factory()->create();

		$user = User::factory()->lehrer()->create();
		$user->klassen()->attach(id: $klasse);

		$schueler = Schueler::factory()->for(factory: $klasse)->create();

		Lernabschnitt::factory()
			->for(factory: $schueler)
			->create(attributes: [
				'fehlstundenGesamt' => 3,
				'fehlstundenGesamtUnentschuldigt' => 1,
			]);

		$this->actingAs(user: $user)->postJson(
			uri: route(name: $this->urlSchuelerFehlstundenGesamtUnentschuldigt, parameters: $schueler),
			data: ['value' => 3]
		)->assertNoContent();

		$this->assertDatabaseHas(
			table: 'lernabschnitte', data: ['schueler_id' => $schueler->id, 'fehlstundenGesamtUnentschuldigt' => 3]
		)->assertDatabaseMissing(
			table: 'lernabschnitte', data: ['schueler_id' => $schueler->id, 'fehlstundenGesamtUnentschuldigt' => 1]
		);
	}

	public function test_user_cannot_set_leistung_fehlstunden_gesamt_smaller_than_fehlstunden_unentschuldigt(): void
	{
		$lerngruppe = Lerngruppe::factory()->create();

		$user = User::factory()->lehrer()->create();
		$user->lerngruppen()->attach(id: $lerngruppe);

		$leistung = Leistung::factory()->for(factory: $lerngruppe)->create(attributes: [
			'fehlstundenGesamt' => 3,
			'fehlstundenUnentschuldigt' => 3,
		]);

		$this->actingAs(user: $user)->postJson(
			uri: route(name: $this->urlLeistungFehlstundenGesamt, parameters: $leistung),
			data: ['value' => 1]
		)->assertUnprocessable();

		$this->assertDatabaseHas(
			table: 'leistungen', data: ['id' => $leistung->id, 'fehlstundenGesamt' => 3]
		)->assertDatabaseMissing(
			table: 'leistungen', data: ['id' => $leistung->id, 'fehlstundenGesamt' => 1]
		);
	}

	public function test_user_cannot_set_leistung_fehlstunden_unentschuldigt_greater_than_fehlstunden_gesamt(): void
	{
		$lerngruppe = Lerngruppe::factory()->create();

		$user = User::factory()->lehrer()->create();
		$user->lerngruppen()->attach(id: $lerngruppe);

		$leistung = Leistung::factory()->for(factory: $lerngruppe)->create(attributes: [
			'fehlstundenGesamt' => 3,
			'fehlstundenUnentschuldigt' => 3,
		]);

		$this->actingAs(user: $user)->postJson(
			uri: route(name: $this->urlLeistungFehlstundenUnentschuldigt, parameters: $leistung),
			data: ['value' => 5]
		)->assertUnprocessable();

		$this->assertDatabaseHas(
			table: 'leistungen', data: ['id' => $leistung->id, 'fehlstundenUnentschuldigt' => 3]
		)->assertDatabaseMissing(
			table: 'leistungen', data: ['id' => $leistung->id, 'fehlstundenUnentschuldigt' => 5]
		);
	}

	public function test_user_cannot_set_schueler_fehlstunden_gesamt_smaller_than_fehlstunden_gesamt_unentschuldigt(): void
	{
		$klasse = Klasse::factory()->create();

		$user = User::factory()->lehrer()->create();
		$user->klassen()->attach(id: $klasse);

		$schueler = Schueler::factory()->for(factory: $klasse)->create();

		Lernabschnitt::factory()
			->for(factory: $schueler)
			->create(attributes: [
				'fehlstundenGesamt' => 3,
				'fehlstundenGesamtUnentschuldigt' => 3,
			]);

		$this->actingAs(user: $user)->postJson(
			uri: route(name: $this->urlSchuelerFehlstundenGesamt, parameters: $schueler),
			data: ['value' => 1]
		)->assertUnprocessable();

		$this->assertDatabaseHas(
			table: 'lernabschnitte', data: ['schueler_id' => $schueler->id, 'fehlstundenGesamt' => 3]
		)->assertDatabaseMissing(
			table: 'lernabschnitte', data: ['schueler_id' => $schueler->id, 'fehlstundenGesamt' => 1]
		);
	}

	public function test_user_cannot_set_schueler_fehlstunden_gesamt_unentschuldigt_greater_than_fehlstunden_gesamt(): void
	{
		$klasse = Klasse::factory()->create();

		$user = User::factory()->lehrer()->create();
		$user->klassen()->attach(id: $klasse);

		$schueler = Schueler::factory()->for(factory: $klasse)->create();

		Lernabschnitt::factory()
			->for(factory: $schueler)
			->create(attributes: [
				'fehlstundenGesamt' => 3,
				'fehlstundenGesamtUnentschuldigt' => 3,
			]);

		$this->actingAs(user: $user)->postJson(
			uri: route(name: $this->urlSchuelerFehlstundenGesamtUnentschuldigt, parameters: $schueler),
			data: ['value' => 5]
		)->assertUnprocessable();

		$this->assertDatabaseHas(
			table: 'lernabschnitte', data: ['schueler_id' => $schueler->id, 'fehlstundenGesamtUnentschuldigt' => 3]
		)->assertDatabaseMissing(
			table: 'lernabschnitte', data: ['schueler_id' => $schueler->id, 'fehlstundenGesamtUnentschuldigt' => 5]
		);
	}

	public function test_user_cannot_set_leistung_fehlstunden_gesamt_for_leistung_not_in_a_common_lerngruppe(): void
	{
		$leistung = Leistung::factory()->create(attributes: [
			'fehlstundenGesamt' => 3,
		]);

		$this->actingAs(user: User::factory()->lehrer()->create())
			->postJson(
				uri: route(name: $this->urlLeistungFehlstundenGesamt, parameters: $leistung),
				data: ['value' => 5]
			)->assertForbidden();

		$this->assertDatabaseHas(
			table: 'leistungen', data: ['id' => $leistung->id, 'fehlstundenGesamt' => 3]
		)->assertDatabaseMissing(
			table: 'leistungen', data: ['id' => $leistung->id, 'fehlstundenGesamt' => 5]
		);
	}

	public function test_user_cannot_set_leistung_fehlstunden_unentschuldigt_for_leistung_not_in_a_common_lerngruppe(): void
	{
		$leistung = Leistung::factory()->create(attributes: [
			'fehlstundenUnentschuldigt' => 3,
		]);

		$this->actingAs(user: User::factory()->lehrer()->create())->postJson(
			uri: route(name: $this->urlLeistungFehlstundenUnentschuldigt, parameters: $leistung),
			data: ['value' => 5]
		)->assertForbidden();

		$this->assertDatabaseHas(
			table: 'leistungen', data: ['id' => $leistung->id, 'fehlstundenUnentschuldigt' => 3]
		)->assertDatabaseMissing(
			table: 'leistungen', data: ['id' => $leistung->id, 'fehlstundenUnentschuldigt' => 5]
		);
	}

	public function test_user_cannot_set_schueler_fehlstunden_gesamt_for_schueler_not_in_a_common_klasse(): void
	{
		$schueler = Schueler::factory()->create();

		Lernabschnitt::factory()
			->for(factory: $schueler)
			->create(attributes: [
				'fehlstundenGesamt' => 3,
				'fehlstundenGesamtUnentschuldigt' => 3,
			]);

		$this->actingAs(user: User::factory()->lehrer()->create())
			->postJson(
				uri: route(name: $this->urlSchuelerFehlstundenGesamt, parameters: $schueler),
				data: ['value' => 5]
			)->assertForbidden();

		$this->assertDatabaseHas(
			table: 'lernabschnitte', data: ['schueler_id' => $schueler->id, 'fehlstundenGesamt' => 3]
		)->assertDatabaseMissing(
			table: 'lernabschnitte', data: ['schueler_id' => $schueler->id, 'fehlstundenGesamt' => 5]
		);
	}

	public function test_user_cannot_set_schueler_fehlstunden_gesamt_unentschuldigt_for_schueler_not_in_a_common_klasse(): void
	{
		$schueler = Schueler::factory()->create();

		Lernabschnitt::factory()
			->for(factory: $schueler)
			->create(attributes: [
				'fehlstundenGesamt' => 3,
				'fehlstundenGesamtUnentschuldigt' => 3,
			]);

		$this->actingAs(user: User::factory()->lehrer()->create())
			->postJson(
				uri: route(name: $this->urlSchuelerFehlstundenGesamtUnentschuldigt, parameters: $schueler),
				data: ['value' => 5]
			)->assertForbidden();

		$this->assertDatabaseHas(
			table: 'lernabschnitte', data: ['schueler_id' => $schueler->id, 'fehlstundenGesamtUnentschuldigt' => 3]
		)->assertDatabaseMissing(
			table: 'lernabschnitte', data: ['schueler_id' => $schueler->id, 'fehlstundenGesamtUnentschuldigt' => 5]
		);
	}

	public function test_guest_cannot_set_leistung_fehlstunden_gesamt(): void
	{
		$this->postJson(
			uri: route(name: $this->urlLeistungFehlstundenGesamt, parameters: Leistung::factory()->create()),
			data: ['value' => 5]
		)->assertUnauthorized();
	}

	public function test_guest_cannot_set_leistung_fehlstunden_unentschuldigt(): void
	{
		$this->postJson(
			uri: route(name: $this->urlLeistungFehlstundenUnentschuldigt, parameters: Leistung::factory()->create()),
			data: ['value' => 5]
		)->assertUnauthorized();
	}

	public function test_guest_cannot_set_schueler_fehlstunden_gesamt(): void
	{
		$this->postJson(
			uri: route(name: $this->urlSchuelerFehlstundenGesamt, parameters: Leistung::factory()->create()),
			data: ['value' => 5]
		)->assertUnauthorized();
	}

	public function test_guest_cannot_set_schueler_fehlstunden_gesamt_unentschuldigt(): void
	{
		$this->postJson(
			uri: route(name: $this->urlSchuelerFehlstundenGesamtUnentschuldigt, parameters: Leistung::factory()->create()),
			data: ['value' => 5]
		)->assertUnauthorized();
	}
}
