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

    /**
     * Leistungen Fehlstunden gesamt Endpoint
     *
     * @var string
     */
    private string $urlLeistungFehlstundenGesamt = 'api.fehlstunden.leistung.gesamt';

    /**
     * Leistungen Fehlstunden unentschuldigt Endpoint
     *
     * @var string
     */
	private string $urlLeistungFehlstundenUnentschuldigt = 'api.fehlstunden.leistung.unentschuldigt';

    /**
     * Schueler Fehlstunden gesamt Endpoint
     *
     * @var string
     */
	private string $urlSchuelerFehlstundenGesamt = 'api.fehlstunden.schueler.gesamt';

    /**
     * Schueler Fehlstunden unentschuldigt Endpoint
     *
     * @var string
     */
	private string $urlSchuelerFehlstundenGesamtUnentschuldigt = 'api.fehlstunden.schueler.gesamt_unentschuldigt';

    /**
     * Test if user can set leistung fehlstunden facht
     *
     * @return void
     */
	public function test_user_can_set_leistung_fehlstunden_facht(): void
	{
		$lerngruppe = Lerngruppe::factory()->create();
		$user = User::factory()->lehrer()->create();
		$user->lerngruppen()->attach($lerngruppe);
		$leistung = Leistung::factory()
			->for($lerngruppe)
			->create([
				'fehlstundenFach' => 3,
				'fehlstundenUnentschuldigtFach' => 3,
			]);

		$this->actingAs($user)
            ->postJson(route($this->urlLeistungFehlstundenGesamt,  $leistung), ['value' => 5])
            ->assertNoContent();

		$this->assertDatabaseHas('leistungen', ['id' => $leistung->id, 'fehlstundenFach' => 5])
            ->assertDatabaseMissing('leistungen', ['id' => $leistung->id, 'fehlstundenFach' => 3]);
	}

    /**
     * Test if user can set leistung fehlstunden unentschuldigt fach
     *
     * @return void
     */
	public function test_user_can_set_leistung_fehlstunden_unentschuldigt_fach(): void
	{
		$lerngruppe = Lerngruppe::factory()->create();
		$user = User::factory()->lehrer()->create();
		$user->lerngruppen()->attach($lerngruppe);
		$leistung = Leistung::factory()
			->for($lerngruppe)
			->create([
				'fehlstundenFach' => 5,
				'fehlstundenUnentschuldigtFach' => 3,
			]);

		$this->actingAs($user)
            ->postJson(route($this->urlLeistungFehlstundenUnentschuldigt, $leistung), ['value' => 5])
            ->assertNoContent();

		$this->assertDatabaseHas('leistungen', ['id' => $leistung->id, 'fehlstundenUnentschuldigtFach' => 5])
            ->assertDatabaseMissing('leistungen', ['id' => $leistung->id, 'fehlstundenUnentschuldigtFach' => 3]);
	}

    /**
     * Test if user can set schueler fehlstunden gesamt
     *
     * @return void
     */
	public function test_user_can_set_schueler_fehlstunden_gesamt(): void
	{
		$klasse = Klasse::factory()->create();
		$user = User::factory()->lehrer()->create();
		$user->klassen()->attach($klasse);
		$schueler = Schueler::factory()->for($klasse)->create();
		Lernabschnitt::factory()
			->for($schueler)
			->create([
				'fehlstundenGesamt' => 3,
				'fehlstundenGesamtUnentschuldigt' => 3,
			]);

		$this->actingAs($user)
            ->postJson(route($this->urlSchuelerFehlstundenGesamt, $schueler), ['value' => 5])
            ->assertNoContent();

		$this->assertDatabaseHas('lernabschnitte', ['schueler_id' => $schueler->id, 'fehlstundenGesamt' => 5])
            ->assertDatabaseMissing('lernabschnitte', ['schueler_id' => $schueler->id, 'fehlstundenGesamt' => 3]);
	}

    /**
     * Test if user can set schueler fehlstunden gesamt unentschuldigt
     *
     * @return void
     */
	public function test_user_can_set_schueler_fehlstunden_gesamt_unentschuldigt(): void
	{
		$klasse = Klasse::factory()->create();
		$user = User::factory()->lehrer()->create();
		$user->klassen()->attach($klasse);
		$schueler = Schueler::factory()->for($klasse)->create();
		Lernabschnitt::factory()
			->for($schueler)
			->create([
				'fehlstundenGesamt' => 3,
				'fehlstundenGesamtUnentschuldigt' => 1,
			]);

		$this->actingAs($user)
            ->postJson(route($this->urlSchuelerFehlstundenGesamtUnentschuldigt, $schueler), ['value' => 3])
            ->assertNoContent();

		$this->assertDatabaseHas(
			'lernabschnitte', ['schueler_id' => $schueler->id, 'fehlstundenGesamtUnentschuldigt' => 3]
		)->assertDatabaseMissing(
			'lernabschnitte', ['schueler_id' => $schueler->id, 'fehlstundenGesamtUnentschuldigt' => 1]
		);
	}

    /**
     * Test if user cannot set leistung fehlstunden gesamt smaller than fehlstunden unentschuldigt
     *
     * @return void
     */
	public function test_user_cannot_set_leistung_fehlstunden_gesamt_smaller_than_fehlstunden_unentschuldigt(): void
	{
		$lerngruppe = Lerngruppe::factory()->create();
		$user = User::factory()->lehrer()->create();
		$user->lerngruppen()->attach($lerngruppe);
		$leistung = Leistung::factory()->for($lerngruppe)->create([
			'fehlstundenFach' => 3,
			'fehlstundenUnentschuldigtFach' => 3,
		]);

		$this->actingAs($user)
            ->postJson(route($this->urlLeistungFehlstundenGesamt, $leistung), ['value' => 1])
            ->assertUnprocessable();

		$this->assertDatabaseHas('leistungen', ['id' => $leistung->id, 'fehlstundenFach' => 3])
            ->assertDatabaseMissing('leistungen', ['id' => $leistung->id, 'fehlstundenFach' => 1]);
	}

    /**
     * Test if user cannot set leistung fehlstunden unentschuldigt greater than fehlstunden gesamt
     *
     * @return void
     */
	public function test_user_cannot_set_leistung_fehlstunden_unentschuldigt_greater_than_fehlstunden_gesamt(): void
	{
		$lerngruppe = Lerngruppe::factory()->create();
		$user = User::factory()->lehrer()->create();
		$user->lerngruppen()->attach($lerngruppe);
		$leistung = Leistung::factory()->for($lerngruppe)->create([
			'fehlstundenFach' => 3,
			'fehlstundenUnentschuldigtFach' => 3,
		]);

		$this->actingAs($user)
            ->postJson(route($this->urlLeistungFehlstundenUnentschuldigt, $leistung), ['value' => 5])
            ->assertUnprocessable();

		$this->assertDatabaseHas('leistungen', ['id' => $leistung->id, 'fehlstundenUnentschuldigtFach' => 3])
            ->assertDatabaseMissing('leistungen', ['id' => $leistung->id, 'fehlstundenUnentschuldigtFach' => 5]);
	}

    /**
     * Test if user cannot set schueler fehlstunden gesamt smaller than fehlstunden gesamt unentschuldigt
     *
     * @return void
     */
	public function test_user_cannot_set_schueler_fehlstunden_gesamt_smaller_than_fehlstunden_gesamt_unentschuldigt(): void
	{
		$klasse = Klasse::factory()->create();
		$user = User::factory()->lehrer()->create();
		$user->klassen()->attach($klasse);
		$schueler = Schueler::factory()->for($klasse)->create();
		Lernabschnitt::factory()
			->for($schueler)
			->create([
				'fehlstundenGesamt' => 3,
				'fehlstundenGesamtUnentschuldigt' => 3,
			]);

		$this->actingAs($user)
            ->postJson(route($this->urlSchuelerFehlstundenGesamt, $schueler), ['value' => 1])->assertUnprocessable();

		$this->assertDatabaseHas('lernabschnitte', ['schueler_id' => $schueler->id, 'fehlstundenGesamt' => 3])
            ->assertDatabaseMissing('lernabschnitte', ['schueler_id' => $schueler->id, 'fehlstundenGesamt' => 1]);
	}

    /**
     * Test if user cannot set schueler fehlstunden gesamt unentschuldigt greater than fehlstunden gesamt
     *
     * @return void
     */
	public function test_user_cannot_set_schueler_fehlstunden_gesamt_unentschuldigt_greater_than_fehlstunden_gesamt(): void
	{
		$klasse = Klasse::factory()->create();
		$user = User::factory()->lehrer()->create();
		$user->klassen()->attach($klasse);
		$schueler = Schueler::factory()->for($klasse)->create();
		Lernabschnitt::factory()
			->for($schueler)
			->create([
				'fehlstundenGesamt' => 3,
				'fehlstundenGesamtUnentschuldigt' => 3,
			]);

		$this->actingAs($user)
            ->postJson(route($this->urlSchuelerFehlstundenGesamtUnentschuldigt, $schueler), ['value' => 5])
            ->assertUnprocessable();

		$this->assertDatabaseHas(
			'lernabschnitte', ['schueler_id' => $schueler->id, 'fehlstundenGesamtUnentschuldigt' => 3]
		)->assertDatabaseMissing(
			'lernabschnitte', ['schueler_id' => $schueler->id, 'fehlstundenGesamtUnentschuldigt' => 5]
		);
	}

    /**
     * Test if user cannot set leistung fehlstunden gesamt for leistung not in a common lerngruppe
     *
     * @return void
     */
	public function test_user_cannot_set_leistung_fehlstunden_gesamt_for_leistung_not_in_a_common_lerngruppe(): void
	{
		$leistung = Leistung::factory()->create(['fehlstundenFach' => 3]);

		$this->actingAs(User::factory()->lehrer()->create())
			->postJson(route($this->urlLeistungFehlstundenGesamt, $leistung), ['value' => 5])
            ->assertForbidden();

		$this->assertDatabaseHas(
			'leistungen', ['id' => $leistung->id, 'fehlstundenFach' => 3]
		)->assertDatabaseMissing(
			'leistungen', ['id' => $leistung->id, 'fehlstundenFach' => 5]
		);
	}

    /**
     * Test if user cannot set leistung fehlstunden unentschuldigt for leistung not in a common lerngruppe
     *
     * @return void
     */
	public function test_user_cannot_set_leistung_fehlstunden_unentschuldigt_for_leistung_not_in_a_common_lerngruppe(): void
	{
		$leistung = Leistung::factory()->create(['fehlstundenUnentschuldigtFach' => 3]);

		$this->actingAs(User::factory()->lehrer()->create())
            ->postJson(route($this->urlLeistungFehlstundenUnentschuldigt, $leistung), ['value' => 5])
            ->assertForbidden();

		$this->assertDatabaseHas('leistungen', ['id' => $leistung->id, 'fehlstundenUnentschuldigtFach' => 3])
            ->assertDatabaseMissing('leistungen', ['id' => $leistung->id, 'fehlstundenUnentschuldigtFach' => 5]);
	}

    /**
     * Test if user cannot set schueler fehlstunden gesamt for schueler not in a common klasse
     *
     * @return void
     */
	public function test_user_cannot_set_schueler_fehlstunden_gesamt_for_schueler_not_in_a_common_klasse(): void
	{
		$schueler = Schueler::factory()->create();

		Lernabschnitt::factory()
			->for($schueler)
			->create([
				'fehlstundenGesamt' => 3,
				'fehlstundenGesamtUnentschuldigt' => 3,
			]);

		$this->actingAs(User::factory()->lehrer()->create())
			->postJson(route($this->urlSchuelerFehlstundenGesamt, $schueler), ['value' => 5])->assertForbidden();

		$this->assertDatabaseHas('lernabschnitte', ['schueler_id' => $schueler->id, 'fehlstundenGesamt' => 3])
            ->assertDatabaseMissing('lernabschnitte', ['schueler_id' => $schueler->id, 'fehlstundenGesamt' => 5]);
	}

    /**
     * Test if user cannot set schueler fehlstunden gesamt unentschuldigt for schueler not in a common klasse
     *
     * @return void
     */
	public function test_user_cannot_set_schueler_fehlstunden_gesamt_unentschuldigt_for_schueler_not_in_a_common_klasse(): void
	{
		$schueler = Schueler::factory()->create();
		Lernabschnitt::factory()
			->for($schueler)
			->create([
				'fehlstundenGesamt' => 3,
				'fehlstundenGesamtUnentschuldigt' => 3,
			]);

		$this->actingAs(User::factory()->lehrer()->create())
			->postJson(route($this->urlSchuelerFehlstundenGesamtUnentschuldigt, $schueler), ['value' => 5])
            ->assertForbidden();

		$this->assertDatabaseHas(
			'lernabschnitte', ['schueler_id' => $schueler->id, 'fehlstundenGesamtUnentschuldigt' => 3]
		)->assertDatabaseMissing(
			'lernabschnitte', ['schueler_id' => $schueler->id, 'fehlstundenGesamtUnentschuldigt' => 5]
		);
	}

    /**
     * Test if guest cannot set leistung fehlstunden gesamt
     *
     * @return void
     */
	public function test_guest_cannot_set_leistung_fehlstunden_gesamt(): void
	{
		$this->postJson(route($this->urlLeistungFehlstundenGesamt, Leistung::factory()->create()), ['value' => 5])
            ->assertUnauthorized();
	}

    /**
     * Test if guest cannot set leistung fehlstunden unentschuldigt
     *
     * @return void
     */
	public function test_guest_cannot_set_leistung_fehlstunden_unentschuldigt(): void
	{
		$this->postJson(
			route($this->urlLeistungFehlstundenUnentschuldigt, Leistung::factory()->create()),
            ['value' => 5]
		)->assertUnauthorized();
	}

    /**
     * Test if guest cannot set schueler fehlstunden gesamt
     *
     * @return void
     */
	public function test_guest_cannot_set_schueler_fehlstunden_gesamt(): void
	{
		$this->postJson(
			route($this->urlSchuelerFehlstundenGesamt, Leistung::factory()->create()),
			['value' => 5]
		)->assertUnauthorized();
	}

    /**
     * Test if guest cannot set schueler fehlstunden gesamt unentschuldigt
     *
     * @return void
     */
	public function test_guest_cannot_set_schueler_fehlstunden_gesamt_unentschuldigt(): void
	{
		$this->postJson(
			route($this->urlSchuelerFehlstundenGesamtUnentschuldigt, Leistung::factory()->create()),
			['value' => 5]
		)->assertUnauthorized();
	}
}
