<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Tests\TestCase;

class BattletagControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    /** @test */
    public function it_redirects_to_battlenet_provider()
    {
        // Mock the Socialite driver method and the redirect method
        Socialite::shouldReceive('driver')->with('battle_net')->andReturnSelf()->shouldReceive('redirect')->andReturn(redirect('http://battlenet-mock-url.com'));

        $response = $this->get(route('battlenet-provider-redirect')); // Assuming you named your route

        $response->assertRedirect('http://battlenet-mock-url.com');
    }

    /** @test */
    public function it_handles_battlenet_callback_successfully()
    {
        $socialiteUser = new SocialiteUser();
        $socialiteUser->id = 123;
        $socialiteUser->name = 'Test User';
        $socialiteUser->attributes = [
            'sub' => '123',
            'name' => 'Test User'
        ];

        Socialite::shouldReceive('driver')->with('battle_net')->andReturnSelf()->shouldReceive('user')->andReturn($socialiteUser);

        $response = $this->get(route('battlenet-provider-callback')); // Assuming you named your route

        $this->assertDatabaseHas('users', [
            'sub' => '123',
            'name' => 'Test User'
        ]);

        $response->assertRedirect('http://localhost:3000/callback');
        $this->assertAuthenticated();
    }

    /** @test */
    public function it_handles_battlenet_callback_error()
    {
        Socialite::shouldReceive('driver')->with('battle_net')->andReturnSelf()->shouldReceive('user')->andThrow(new \Exception('Unable to authenticate.'));

        $response = $this->get(route('battlenet-provider-callback')); // Assuming you named your route

        $response->assertStatus(500)->assertJson(['message' => 'Unable to authenticate.']);
    }
}
