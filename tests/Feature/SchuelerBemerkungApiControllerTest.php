<?php

namespace Tests\Feature;

use App\Models\Bemerkung;
use App\Models\Schueler;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SchuelerBemerkungApiControllerTest extends TestCase
{
	use RefreshDatabase;

    /**
     * Endpoint url
     *
     * @var string
     */
    private string $url = 'api.schueler_bemerkung';

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
     * First allowed column
     *
     * @var string
     */
    private string $firstAllowedColumn = Bemerkung::ALLOWED_BEMERKUNGEN[0];

    /**
     * Test if user can update
     *
     * @return void
     */
    public function test_users_can_update(): void
	{
		foreach (Bemerkung::ALLOWED_BEMERKUNGEN as $key) {
			$schueler = Schueler::factory()->create();
			$bemerkung = Bemerkung::factory()->for($schueler)->create([$key => $this->old]);
			$user = User::factory()->create();
			$user->klassen()->attach($schueler->klasse_id);

			$this->actingAs($user)
                ->postJson(route($this->url, $schueler), ['key' => $key, 'value' => $this->new])
                ->assertNoContent();

			$this->assertDatabaseHas('bemerkungen', ['id' => $bemerkung->id, $key => $this->new])
				->assertDatabaseMissing('bemerkungen', ['id' => $bemerkung->id, $key => $this->old]);
		}
	}

    /**
     * Test if administrators cannot update
     *
     * @return void
     */
	public function test_administrators_cannot_update(): void
	{
		$schueler = Schueler::factory()->create();
		$bemerkung = Bemerkung::factory()
			->for($schueler)
			->create([$this->firstAllowedColumn => $this->old]);

		$this->actingAs(User::factory()->administrator()->create())
            ->postJson(route($this->url, $schueler), ['key' => $this->firstAllowedColumn, 'value' => $this->new])
            ->assertForbidden();

		$this->assertDatabaseHas('bemerkungen', ['id' => $bemerkung->id, $this->firstAllowedColumn => $this->old])
            ->assertDatabaseMissing('bemerkungen', ['id' => $bemerkung->id, $this->firstAllowedColumn => $this->new]);
	}

    /**
     * Test if guests cannot update
     *
     * @return void
     */
	public function test_guest_cannot_update(): void
	{
		$schueler = Schueler::factory()->create();
		$bemerkung = Bemerkung::factory()
			->for($schueler)
			->create([$this->firstAllowedColumn => $this->old]);

		$this->postJson(route($this->url, $schueler), ['key' => $this->firstAllowedColumn, 'value' => $this->new])
            ->assertUnauthorized();

		$this->assertDatabaseHas('bemerkungen', ['id' => $bemerkung->id, $this->firstAllowedColumn => $this->old])
            ->assertDatabaseMissing('bemerkungen', ['id' => $bemerkung->id, $this->firstAllowedColumn => $this->new]);
	}

    /**
     * Test if timestamp is updated
     *
     * @return void
     */
	public function test_timestamp_is_updated(): void
	{
		foreach (Bemerkung::ALLOWED_BEMERKUNGEN as $key) {
			$timestamp = now()->subHour()->format('Y-m-d H:i:s.u');
			$schueler = Schueler::factory()->create();
			$bemerkung = Bemerkung::factory()->for($schueler)->create([$key => $this->old, "ts{$key}" => $timestamp]);
			$user = User::factory()->create();
			$user->klassen()->attach($schueler->klasse_id);

			$this->actingAs($user)
                ->postJson(route($this->url, $schueler), ['key' => $key, 'value' => $this->new])
			    ->assertNoContent();

			$this->assertDatabaseMissing('bemerkungen', ['id' => $bemerkung->id, "ts{$key}" => $timestamp]);
		}
	}

    /**
     * Test if Users Cannot Update Bemerkungen of a Schueler Not in Their Own Class
     *
     * @return void
     */
	public function test_users_cannot_update_bemerkungen_of_a_schueler_not_in_their_own_class(): void
	{
		$schueler = Schueler::factory()->create();
		$bemerkung = Bemerkung::factory()->for($schueler)->create([$this->firstAllowedColumn => $this->old]);

		$this->actingAs(User::factory()->create())
            ->postJson(route($this->url, $schueler), ['key' => $this->firstAllowedColumn, 'value' => $this->new])
            ->assertForbidden();

		$this->assertDatabaseHas('bemerkungen', ['id' => $bemerkung->id, $this->firstAllowedColumn => $this->old])
            ->assertDatabaseMissing('bemerkungen', ['id' => $bemerkung->id, $this->firstAllowedColumn => $this->new]);
	}

    /**
     * Test if Test Users Cannot Update Bemerkungen That Are Not in the Allowed List
     *
     * @return void
     */
	public function test_users_cannot_update_bemerkungen_that_are_not_in_the_allowed_list(): void
	{
		$key = 'randomInvalidKey' . time();
		$schueler = Schueler::factory()->has(Bemerkung::factory())->create();
		$user = User::factory()->create();
		$user->klassen()->attach($schueler->klasse_id);

		$this->actingAs($user)
            ->postJson(route($this->url, $schueler), ['key' => $key, 'value' => $this->new])
            ->assertUnprocessable();
	}
}
