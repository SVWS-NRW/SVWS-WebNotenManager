<?php

namespace Tests\Feature;

use App\Models\Leistung;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FachbezogeneBemerkungApiControllerTest extends TestCase
{
	use RefreshDatabase;

    /**
     * Endpoint URL
     *
     * @var string
     */
    private string $url = 'api.fachbezogene_bemerkung';

    /**
     * Old value
     *
     * @var string
     */
    private string $old = 'Lorem ipsum';

    /**
     * New value
     *
     * @var string
     */
    private string $new = 'Dolor sit amet';

    /**
     * Test if users can update
     *
     * @return void
     */
	public function test_users_can_update(): void
	{
		$this->actingAs(User::factory()->create());

		$leistung = Leistung::factory()->create([
			'fachbezogeneBemerkungen' => $this->old,
		]);

		$this->postJson(route($this->url, $leistung), ['bemerkung' => $this->new])
            ->assertNoContent();

		$this->assertDatabaseHas('leistungen', ['id' => $leistung->id, 'fachbezogeneBemerkungen' => $this->new])
            ->assertDatabaseMissing('leistungen', ['id' => $leistung->id, 'fachbezogeneBemerkungen' => $this->old]);
	}

    /**
     * Test if guest cannot update
     *
     * @return void
     */
	public function test_guest_cannot_update(): void
	{
		$leistung = Leistung::factory()->create(['fachbezogeneBemerkungen' => $this->old]);

		$this->postJson(route($this->url, $leistung), ['bemerkung' => $this->new])
            ->assertUnauthorized();

		$this->assertDatabaseHas('leistungen', ['id' => $leistung->id, 'fachbezogeneBemerkungen' => $this->old])
            ->assertDatabaseMissing('leistungen', ['id' => $leistung->id, 'fachbezogeneBemerkungen' => $this->new]);
	}

    /**
     * Test if ts is being updated
     *
     * @return void
     */
	public function test_ts_is_being_updated(): void
	{
		$this->actingAs(User::factory()->create());
		$timestamp = now()->subHour()->format('Y-m-d H:i:s.u');

		$leistung = Leistung::factory()->create([
			'fachbezogeneBemerkungen' => $this->old,
			'tsFachbezogeneBemerkungen' => $timestamp,
		]);

		$response = $this->postJson(route($this->url, $leistung), ['bemerkung' => $this->new])
            ->assertNoContent();

		$this->assertDatabaseMissing('leistungen', [
			'id' => $leistung->id,
			'tsFachbezogeneBemerkungen' => $timestamp,
		]);
	}
}
