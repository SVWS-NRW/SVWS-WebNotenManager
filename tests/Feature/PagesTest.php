<?php

namespace Tests\Feature;

use App\Models\Klasse;
use App\Models\Schueler;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PagesTest extends TestCase
{
	use RefreshDatabase;

    /**
     * Test Impressum Is Being Rendered
     *
     * @return void
     */
    public function test_impressum_is_being_rendered(): void
	{
		$this->get(route('impressum'))
            ->assertOk();
	}

    /**
     * Test if Datenschutz is being rendered.
     *
     * @return void
     */
	public function test_datenschutz_is_being_rendered(): void
	{
		$this->get(route('datenschutz'))
			->assertOk();
	}

    /**
     * Test if Barrierefreiheit is being rendered
     *
     * @return void
     */
	public function test_barrierefreiheit_is_being_rendered(): void
	{
		$this->get(route('barrierefreiheit'))
            ->assertOk();
	}

    /**
     * Test if Login page is being rendered
     *
     * @return void
     */
	public function test_login_is_being_rendered(): void
	{
		$this->get(route('login'))
            ->assertOk();
    }

    /**
     * Test if Login redirects to Mein Unterricht if logged in
     *
     * @return void
     */
	public function test_login_redirects_to_mein_unterricht_if_logged_in(): void
	{
		$this->actingAs(User::factory()->create())
			->get(route('login'))
			->assertRedirect(route('mein_unterricht'));
	}

    /**
     * Test if Mein Unterricht is not accessible if not logged in
     *
     * @return void
     */
	public function test_mein_unterricht_is_not_accessible_if_not_logged_in(): void
	{
		$this->get(route('mein_unterricht'))
            ->assertRedirect(route('login'));
	}

    /**
     * Test if Leistungsdatenuebersicht is not accessible if not logged in
     *
     * @return void
     */
	public function test_leistungsdatenuebersicht_is_not_accessible_if_not_logged_in(): void
	{
		$this->get(route('leistungsdatenuebersicht'))
            ->assertRedirect(route('login'));
	}

    /**
     * Test if Klassenleitung is not accessible if not logged in
     *
     * @return void
     */
	public function test_klassenleitung_is_not_accessible_if_not_logged_in(): void
	{
		$this->get(route('klassenleitung'))
			->assertRedirect(route('login'));
	}

    /**
     * Test if Mein Unterricht is being rendered if logged in
     *
     * @return void
     */
	public function test_mein_unterricht_is_being_rendered_if_logged_in(): void
	{
		$this->actingAs(User::factory()->create())
			->get(route('mein_unterricht'))
			->assertOk();
	}

    /**
     * Test if Leistungsdatenuebersicht is being rendered if logged in
     *
     * @return void
     */
	public function test_leistungsdatenuebersicht_is_being_rendered_if_logged_in(): void
	{
		$this->actingAs(User::factory()->create())
			->get(route('leistungsdatenuebersicht'))
			->assertOk();
	}

    /**
     * Test if Klassenleitung redirects Lehrer to Mein Unterricht if logged in with no klassen assigned
     *
     * @return void
     */
	public function test_klassenleitung_redirects_lehrer_to_mein_unterricht_if_logged_in_with_no_klassen_assigned(): void
	{
		$this->actingAs(User::factory()->lehrer()->create())
			->get(route('klassenleitung'))
			->assertRedirect(route('mein_unterricht'));
	}

    /**
     * Test if Klassenleitung renders to administrator
     *
     * @return void
     */
	public function test_klassenleitung_renders_to_administrator(): void
	{
		$this->actingAs(User::factory()->administrator()->create())
			->get(route('klassenleitung'))
			->assertOk();
	}

    /**
     * Test if Klassenleitung is being rendered if logged in and has klassen assigned
     *
     * @return void
     */
	public function test_klassenleitung_is_being_rendered_if_logged_in_and_has_klassen_assigned(): void
	{
		$klasse = Klasse::factory()->create();
		$user = User::factory()->create();
		$user->klassen()->attach($klasse);

		$this->actingAs($user)
			->get(route('klassenleitung'))
			->assertOk();
	}
}
