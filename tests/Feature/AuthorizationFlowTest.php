<?php

namespace Tests\Feature;

use App\Models\User;
use App\Socialite\BattleNetProvider;
use Laravel\Socialite\Two\User as SocialiteUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;
use Mockery;
use Tests\TestCase;

class AuthorizationFlowTest extends TestCase
{
    /**
     * Test a successful Battle.net OAuth flow.
     *
     * @return void
     */
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();


        if (!env('DEV_BATTLETAG') || !env('DEV_BATTLETAG_SUB')) {
            $this->fail("DEV_BATTLETAG and DEV_BATTLETAG_SUB must be set in your .env file to run this test.\n");
            return;
        }

        Http::fake([
            'battle.net/oauth/authorize' => Http::response([], 200),
            'battle.net/oauth/token' => Http::response([
                "token_type" => "bearer",
                "expires_in" => 86399,
                "access_token" => "USVpe3EizBul8AM6miqEmDVaR8q1PdWwio",
                "sub" => env('DEV_BATTLETAG_SUB'),
            ], 200),
            'battle.net/oauth/userinfo' => Http::response([
                'sub' => env('DEV_BATTLETAG_SUB'),
                'id' => env('DEV_BATTLETAG_SUB'),
                'battletag' => env('DEV_BATTLETAG'),
            ], 200),
        ]);
    }

    /** @test */
    public function test_successful_oauth_flow_user_creation_and_authorization()
    {
        $mockedUser = new SocialiteUser();
        $mockedUser->id = env('DEV_BATTLETAG_SUB');
        $mockedUser->nickname = null;
        $mockedUser->name = env('DEV_BATTLETAG');
        $mockedUser->email = null;
        $mockedUser->avatar = null;
        $mockedUser->user = [
            "sub" => "env('DEV_BATTLETAG_SUB')",
            "id" => env('DEV_BATTLETAG_SUB'),
            "battletag" => env('DEV_BATTLETAG')
        ];

        $mockedUser->attributes = [
            "id" => env('DEV_BATTLETAG_SUB'),
            "name" => env('DEV_BATTLETAG'),
            "sub" => env('DEV_BATTLETAG_SUB'),
        ];

        $mockedProvider = Mockery::mock(BattleNetProvider::class)->makePartial();
        $mockedProvider->shouldReceive('redirect')->andReturn(redirect('oauth.battle.net/oauth/authorize'));
        $mockedProvider->shouldReceive('user')->andReturn($mockedUser);
        Socialite::shouldReceive('driver')->with('battle_net')->andReturn($mockedProvider);

        // Initiate the OAuth flow
        $response = $this->get('/battlenet/login');
        $response->assertRedirect('oauth.battle.net/oauth/authorize');
        $response = $this->get('/battlenet/callback?code=some_mocked_code');
        $response->assertRedirect('http://localhost:3000/callback');

        $this->assertDatabaseHas('users', [
            'sub' => env('DEV_BATTLETAG_SUB'),
            'name' => env('DEV_BATTLETAG'),
        ]);

        $authenticatedUser = User::where('sub', $mockedUser->attributes['sub'])->first();

        $this->assertAuthenticatedAs($authenticatedUser);
    }
}