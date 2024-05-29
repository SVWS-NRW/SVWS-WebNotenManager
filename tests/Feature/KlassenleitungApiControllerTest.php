<?php

namespace Tests\Feature;

use App\Models\Klasse;
use App\Models\Schueler;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KlassenleitungApiControllerTest extends TestCase
{
	use RefreshDatabase;

    /**
     * Endpoint url
     *
     * @var string
     */
	private string $url = 'api.klassenleitung';

    /**
     * Test if user can read schueler from common class
     *
     * @return void
     */
	public function test_user_can_read_schueler_from_common_class(): void
	{
		$klasse = Klasse::factory()->create();
		$user = User::factory()->lehrer()->create();
		$user->klassen()->attach($klasse);
		Schueler::factory(3)->for($klasse)->create();

		$this->actingAs($user)
			->getJson(route($this->url))
			->assertOk()
			->assertJsonCount(3)
			->assertJsonStructure([
				'*' => [
					'id', 'nachname', 'vorname', 'name', 'geschlecht', 'klasse', 'ASV', 'AUE', 'ZB', 'gfs', 'gfsu',
				]
			]);
	}

    /**
     * Test if user cannot read schueler from not common class
     *
     * @return void
     */
	public function test_user_cannot_read_schueler_from_not_common_class(): void
	{
		Schueler::factory(3)->create();
		$klasse = Klasse::factory()->create();
		$user = User::factory()->lehrer()->create();
		$user->klassen()->attach($klasse);

		$this->actingAs($user)
			->getJson(route($this->url))
			->assertOk()
			->assertJsonCount(0);
	}

    /**
     * Test if administrator can read all schueler
     *
     * @return void
     */
	public function test_administrator_can_read_all_schueler(): void
	{
		Schueler::factory(3)->create();

		$this->actingAs(User::factory()->administrator()->create())
			->getJson(route($this->url))
			->assertOk()
			->assertJsonCount(3)
			->assertJsonStructure([
				'*' => [
					'id', 'nachname', 'vorname', 'name', 'geschlecht', 'klasse', 'ASV', 'AUE', 'ZB', 'gfs', 'gfsu',
				]
			]);
	}

    /**
     * Test if guests cannot read
     *
     * @return void
     */
	public function test_guests_cannot_read(): void
	{
		$this->getJson(route($this->url))
			->assertUnauthorized();
	}
}
