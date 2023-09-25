<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogoutControllerTest extends TestCase
{
    use RefreshDatabase;  // If you want to use database transactions during testing

    /** @test */
    public function a_logged_in_user_can_logout()
    {
        // Given: We have a user and they are logged in
        $user = User::factory()->create();

        $this->actingAs($user);

        // When: They hit the logout endpoint
        $this->post(route('logout'));

        // Then: They should be logged out and redirected to the login page
        $this->assertGuest();
    }
}
