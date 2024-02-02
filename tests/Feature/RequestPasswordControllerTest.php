<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RequestPasswordControllerTest extends TestCase
{
	use RefreshDatabase;

    /*
     * Test if Password Request Redirects to Mein Unterricht if Logged In
     *
     * @return void
     */
	public function test_password_request_redirects_to_mein_unterricht_if_logged_in(): void
	{
		$this->actingAs(User::factory()->create())
			->get(route('request_password'))
			->assertRedirect(route('mein_unterricht'));
	}

    /*
     * Test if Password Request Is Being Rendered
     *
     * @return void
     */
	public function test_password_request_is_being_rendered(): void
	{
		$this->get(route('request_password'))
			->assertOk();
	}
}
