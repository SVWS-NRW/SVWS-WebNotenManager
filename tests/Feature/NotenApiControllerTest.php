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

	private string $url = 'api.noten';

	public function test_users_can_set(): void
	{
		$this->actingAs(user: User::factory()->create());

		$old = Note::factory()->create();
		$new = Note::factory()->create();

		$leistung = Leistung::factory()->create(attributes: ['note_id' => $old->id]);

		$response = $this->postJson(uri: route(name: $this->url, parameters: $leistung), data: ['note' => $new->kuerzel]);

		$response->assertNoContent();

		$this->assertDatabaseHas(table: 'leistungen', data: ['id' => $leistung->id, 'note_id' => $new->id])
			->assertDatabaseMissing(table: 'leistungen', data: ['id' => $leistung->id, 'note_id' => $old->id]);
	}

	public function test_users_can_unset(): void
	{
		$this->actingAs(user: User::factory()->create());

		$note = Note::factory()->create();

		$leistung = Leistung::factory()->create(attributes: ['note_id' => $note->id]);

		$response = $this->postJson(uri: route(name: $this->url, parameters: $leistung), data: ['note' => '']);

		$response->assertNoContent();

		$this->assertDatabaseHas(table: 'leistungen', data: ['id' => $leistung->id, 'note_id' => null])
			->assertDatabaseMissing(table: 'leistungen', data: ['id' => $leistung->id, 'note_id' => $note->id]);
	}

	public function test_user_can_only_set_a_valid_note(): void
	{
		$this->actingAs(user: User::factory()->create());

		$validNote = Note::factory()->create(attributes: ['kuerzel' => 'Valid Note']);
		$invalidNote = 'Invalid Note';

		$leistung = Leistung::factory()->create(attributes: ['note_id' => $validNote->id]);

		$response = $this->postJson(uri: route(name: $this->url, parameters: $leistung), data: ['note' => $invalidNote]);

		$response->assertUnprocessable();

		$this->assertDatabaseHas(table: 'leistungen', data: ['id' => $leistung->id, 'note_id' => $validNote->id]);
	}

	public function test_guest_cannot_update(): void
	{
		$old = Note::factory()->create();
		$new = Note::factory()->create();

		$leistung = Leistung::factory()->create(attributes: ['note_id' => $old->id]);

		$response = $this->postJson(uri: route(name: $this->url, parameters: $leistung), data: ['note_id' => $new->kuerzel]);

		$response->assertUnauthorized();

		$this->assertDatabaseHas(table: 'leistungen', data: ['id' => $leistung->id, 'note_id' => $old->id])
			->assertDatabaseMissing(table: 'leistungen', data: ['id' => $leistung->id, 'note_id' => $new->id]);
	}

	public function test_ts_is_being_updated(): void
	{
		$this->actingAs(user: User::factory()->create());
		$timestamp = now()->subHour()->format(format: 'Y-m-d H:i:s.u');

		$old = Note::factory()->create();
		$new = Note::factory()->create();

		$leistung = Leistung::factory()->create(attributes: ['note_id' => $old, 'tsNote' => $timestamp]);

		$response = $this->postJson(uri: route(name: $this->url, parameters: $leistung), data: ['note' => $new->kuerzel]);

		$response->assertNoContent();

		$this->assertDatabaseMissing(table: 'leistungen', data: ['id' => $leistung->id, 'tsNote' => $timestamp]);
	}
}
