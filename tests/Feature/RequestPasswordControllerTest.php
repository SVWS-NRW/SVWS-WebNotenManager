<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RequestPasswordControllerTest extends TestCase
{
	use RefreshDatabase;

	public function test_password_request_redirects_to_mein_unterricht_if_logged_in(): void
	{
		$this->actingAs(user: User::factory()->create())
			->get(uri: route(name: 'request_password'))
			->assertRedirect(uri: route(name: 'mein_unterricht'));
	}

	public function test_password_request_is_being_rendered(): void
	{
		$this->get(uri: route(name: 'request_password'))
			->assertOk();
	}
}
