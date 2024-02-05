<?php

namespace Tests\Feature;

use App\Models\Floskel;
use App\Models\Floskelgruppe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FloskelnApiControllerTest extends TestCase
{
	use RefreshDatabase;

    /**
     * Endpoint URL
     *
     * @var string
     */
    private string $url = 'api.floskeln';

    /**
     * Test if users can read
     *
     * @return void
     */
	public function test_users_can_read(): void
	{
		$floskelgruppe = Floskelgruppe::factory()
            ->has(Floskel::factory(3), 'floskeln')
            ->create();

		$this->actingAs(User::factory()->create())
            ->getJson(route($this->url, $floskelgruppe->kuerzel))
            ->assertOk()
			->assertJsonCount(3)
			->assertJsonStructure(['*' => ['id', 'kuerzel', 'text']]);
	}

    /**
     * Test if users cannot read from not existing floskelgruppe
     *
     * @return void
     */
	public function test_users_cannot_read_from_not_existing_floskelgruppe(): void
	{
		Floskelgruppe::factory()
            ->has(Floskel::factory(3), 'floskeln')
            ->create(['kuerzel' => 'lorem-ipsum']);

		$this->actingAs(User::factory()->create())
            ->getJson(route($this->url, 'dolor-sit-amet'))
            ->assertNotFound();
	}

    /**
     * Test if guests cannot read
     *
     * @return void
     */
	public function test_guest_cannot_read(): void
    {
		$floskelgruppe = Floskelgruppe::factory()->create();

		$this->getJson(route($this->url, $floskelgruppe->kuerzel))
            ->assertUnauthorized();
    }
}
