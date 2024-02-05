<?php

namespace Tests\Feature;

use App\Models\Fach;
use App\Models\Floskel;
use App\Models\Floskelgruppe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FachbezogeneFloskelnApiControllerTest extends TestCase
{
	use RefreshDatabase;

    /**
     * Endpoint URL
     *
     * @var string
     */
    private string $url = 'api.fachbezogene_floskeln';

    /**
     * Test if users can read
     *
     * @return void
     */
	public function test_users_can_read(): void
	{

		$fach = Fach::factory()->create();
		$floskelgruppe = Floskelgruppe::factory()->create(['kuerzel' => 'FACH']);
		Floskel::factory(3)->for($floskelgruppe)->for($fach)->niveau()->jahrgang()->create();

		$this->actingAs(User::factory()->create())
            ->getJson(route($this->url, $fach))
            ->assertOk()
			->assertJsonCount(3, 'data')
			->assertJsonCount(3, 'niveau')
			->assertJsonCount(3, 'jahrgaenge')
			->assertJsonStructure([
				'data' => [
					'*' => ['kuerzel', 'text', 'niveau', 'jahrgang'],
				],
				'niveau',
				'jahrgaenge',
			]);
	}

    /**
     * Test if guests cannot read
     *
     * @return void
     */
	public function test_guests_cannot_read(): void
	{
		$fach = Fach::factory()->create();
		$floskelgruppe = Floskelgruppe::factory()->create(['kuerzel' => 'FACH']);
		Floskel::factory(3)->for($floskelgruppe)->for($fach)->niveau()->jahrgang()->create();

		$this->getJson(route($this->url, $fach))
            ->assertUnauthorized();
	}
}
