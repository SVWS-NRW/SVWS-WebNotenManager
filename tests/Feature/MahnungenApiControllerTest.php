<?php

namespace Tests\Feature;

use App\Models\Leistung;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MahnungenApiControllerTest extends TestCase
{
	use RefreshDatabase;

    /**
     * Endpoint url
     *
     * @var string
     */
	private string $url = 'api.mahnung';

    /**
     * Test if users can set Mahnungen
     *
     * @return void
     */
    public function test_users_can_set(): void
    {
		$leistung = Leistung::factory()->create(['istGemahnt' => false]);

		$this->actingAs(User::factory()->create())
            ->postJson(route($this->url, $leistung), ['istGemahnt' => true])
            ->assertNoContent();

		$this->assertDatabaseHas('leistungen', ['id' => $leistung->id, 'istGemahnt' => true])
			->assertDatabaseMissing('leistungen', ['id' => $leistung->id, 'istGemahnt' => false]);
    }

    /**
     * Test if users can unset Mahnungen
     *
     * @return void
     */
	public function test_users_can_unset(): void
	{
		$leistung = Leistung::factory()->create(['istGemahnt' => true]);

		$this->actingAs(User::factory()->create())
            ->postJson(route($this->url, $leistung), ['istGemahnt' => false])
            ->assertNoContent();

		$this->assertDatabaseHas('leistungen', ['id' => $leistung->id, 'istGemahnt' => false])
			->assertDatabaseMissing('leistungen', ['id' => $leistung->id, 'istGemahnt' => true]);
	}

    /**
     * Test if guest cannot set Mahnungen
     *
     * @return void
     */
	public function test_guest_cannot_update(): void
	{
		$leistung = Leistung::factory()->create(['istGemahnt' => false]);

		$this->postJson(route($this->url, $leistung), ['istGemahnt' => true])
            ->assertUnauthorized();

		$this->assertDatabaseHas('leistungen', ['id' => $leistung->id, 'istGemahnt' => false])
			->assertDatabaseMissing('leistungen', ['id' => $leistung->id, 'fachbezogeneBemerkungen' => true]);
	}

    /**
     * Test if timestamp is being updated
     *
     * @return void
     */
	public function test_ts_is_being_updated(): void
	{
		$timestamp = now()->subHour()->format('Y-m-d H:i:s.u');

		$leistung = Leistung::factory()->create(['istGemahnt' => false, 'tsIstGemahnt' => $timestamp]);

		$this->actingAs(User::factory()->create())
            ->postJson(route($this->url, $leistung), ['istGemahnt' => true])
            ->assertNoContent();

		$this->assertDatabaseMissing('leistungen', ['id' => $leistung->id, 'tsIstGemahnt' => $timestamp]);
	}
}
