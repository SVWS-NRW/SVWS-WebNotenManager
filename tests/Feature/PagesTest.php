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

	public function test_impressum_is_being_rendered(): void
	{
		$this->get(uri: route(name: 'impressum'))
			->assertOk();
	}

	public function test_datenschutz_is_being_rendered(): void
	{
		$this->get(uri: route(name: 'datenschutz'))
			->assertOk();
	}

	public function test_barrierefreiheit_is_being_rendered(): void
	{
		$this->get(uri: route(name: 'barrierefreiheit'))
			->assertOk();
	}

	public function test_login_is_being_rendered(): void
	{
		$this->get(uri: route(name: 'login'))
			->assertOk();
	}

	public function test_login_redirects_to_mein_unterricht_if_logged_in(): void
	{
		$this->actingAs(user: User::factory()->create())
			->get(uri: route(name: 'login'))
			->assertRedirect(uri: route(name: 'mein_unterricht'));
	}

	public function test_mein_unterricht_is_not_accessible_if_not_logged_in(): void
	{
		$this->get(uri: route(name: 'mein_unterricht'))
			->assertRedirect(uri: route(name: 'login'));
	}

	public function test_leistungsdatenuebersicht_is_not_accessible_if_not_logged_in(): void
	{
		$this->get(uri: route(name: 'leistungsdatenuebersicht'))
			->assertRedirect(uri: route(name: 'login'));
	}

	public function test_klassenleitung_is_not_accessible_if_not_logged_in(): void
	{
		$this->get(uri: route(name: 'klassenleitung'))
			->assertRedirect(uri: route(name: 'login'));
	}

	public function test_mein_unterricht_is_being_rendered_if_logged_in(): void
	{
		$this->actingAs(user: User::factory()->create())
			->get(uri: route(name: 'mein_unterricht'))
			->assertOk();
	}

	public function test_leistungsdatenuebersicht_is_being_rendered_if_logged_in(): void
	{
		$this->actingAs(user: User::factory()->create())
			->get(uri: route(name: 'leistungsdatenuebersicht'))
			->assertOk();
	}

	public function test_klassenleitung_redirects_lehrer_to_mein_unterricht_if_logged_in_with_no_klassen_assigned(): void
	{
		$this->actingAs(user: User::factory()->lehrer()->create())
			->get(uri: route(name: 'klassenleitung'))
			->assertRedirect(uri: route(name: 'mein_unterricht'));
	}

	public function test_klassenleitung_renders_to_administrator(): void
	{
		$this->actingAs(user: User::factory()->administrator()->create())
			->get(uri: route(name: 'klassenleitung'))
			->assertOk();
	}

	public function test_klassenleitung_is_being_rendered_if_logged_in_and_has_klassen_assigned(): void
	{
		$klasse = Klasse::factory()->create();
		$user = User::factory()->create();
		$user->klassen()->attach($klasse);

		$this->actingAs(user: $user)
			->get(uri: route(name: 'klassenleitung'))
			->assertOk();
	}
}
