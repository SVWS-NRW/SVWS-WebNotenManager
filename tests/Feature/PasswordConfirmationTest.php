<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Jetstream\Features;
use Tests\TestCase;

class PasswordConfirmationTest extends TestCase
{
    use RefreshDatabase;

    /*
     * Test if confirm password screen can be rendered
     *
     * @return void
     */
    public function test_confirm_password_screen_can_be_rendered()
    {
        $user = User::factory()->withPersonalTeam()->create();

        $this->actingAs($user)
            ->get('/user/confirm-password')
            ->assertStatus(200);
    }

    /*
     * Test if password can be confirmed
     *
     * @return void
     */
    public function test_password_can_be_confirmed()
    {
        $this->actingAs(User::factory()->create())
            ->post('/user/confirm-password', ['password' => 'password'])
            ->assertRedirect()
            ->assertSessionHasNoErrors();
    }

    /*
     * Test if password is not confirmed with invalid password
     *
     * @return void
     */
    public function test_password_is_not_confirmed_with_invalid_password()
    {
        $this->actingAs(User::factory()->create())
            ->post('/user/confirm-password', ['password' => 'wrong-password'])
            ->assertSessionHasErrors();
    }
}
