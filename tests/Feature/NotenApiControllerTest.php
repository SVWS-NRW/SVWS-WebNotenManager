<?php

namespace Tests\Feature;

use App\Models\Leistung;
use App\Models\Note;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotenApiControllerTest extends TestCase
{
	use RefreshDatabase;

    /**
     * Endpoint url
     *
     * @var string
     */
	private string $url = 'api.noten';

    /**
     * Test if users can set note
     *
     * @return void
     */
    public function test_users_can_set_note(): void
	{
		$old = Note::factory()->create();
		$new = Note::factory()->create();
		$leistung = Leistung::factory()->create(['note_id' => $old->id]);

        $this->actingAs(User::factory()->create())
            ->postJson(route($this->url, $leistung), ['note' => $new->kuerzel])
            ->assertNoContent();

		$this->assertDatabaseHas('leistungen', ['id' => $leistung->id, 'note_id' => $new->id])
			->assertDatabaseMissing('leistungen', ['id' => $leistung->id, 'note_id' => $old->id]);
	}

    /**
     * Test if users can unset note
     *
     * @return void
     */
	public function test_users_can_unset(): void
	{
		$note = Note::factory()->create();
		$leistung = Leistung::factory()->create(['note_id' => $note->id]);

        $this->actingAs(User::factory()->create())
            ->postJson(route($this->url, $leistung), ['note' => ''])
            ->assertNoContent();

		$this->assertDatabaseHas('leistungen', ['id' => $leistung->id, 'note_id' => null])
			->assertDatabaseMissing('leistungen', ['id' => $leistung->id, 'note_id' => $note->id]);
	}

    /**
     * Test if user can only set a valid note
     *
     * @return void
     */
	public function test_user_can_only_set_a_valid_note(): void
	{
		$invalidNote = 'Invalid Note';
		$validNote = Note::factory()->create(['kuerzel' => 'Valid Note']);
		$leistung = Leistung::factory()->create(['note_id' => $validNote->id]);

        $this->actingAs(User::factory()->create())
            ->postJson(route($this->url, $leistung), ['note' => $invalidNote])
            ->assertUnprocessable();

		$this->assertDatabaseHas('leistungen', ['id' => $leistung->id, 'note_id' => $validNote->id]);
	}

    /**
     * Test if guest cannot update
     *
     * @return void
     */
	public function test_guest_cannot_update(): void
	{
		$old = Note::factory()->create();
		$new = Note::factory()->create();
		$leistung = Leistung::factory()->create(['note_id' => $old->id]);

		$this->postJson(route($this->url, $leistung), ['note_id' => $new->kuerzel])
            ->assertUnauthorized();

		$this->assertDatabaseHas('leistungen', ['id' => $leistung->id, 'note_id' => $old->id])
			->assertDatabaseMissing('leistungen', ['id' => $leistung->id, 'note_id' => $new->id]);
	}

    /**
     * Test if timestamp is being updated
     *
     * @return void
     */
	public function test_ts_is_being_updated(): void
	{
		$timestamp = now()->subHour()->format('Y-m-d H:i:s.u');

		$old = Note::factory()->create();
		$new = Note::factory()->create();
		$leistung = Leistung::factory()->create(['note_id' => $old, 'tsNote' => $timestamp]);

		$this->actingAs(User::factory()->create())
            ->postJson(route($this->url, $leistung), ['note' => $new->kuerzel])
            ->assertNoContent();

		$this->assertDatabaseMissing('leistungen', ['id' => $leistung->id, 'tsNote' => $timestamp]);
	}
}
