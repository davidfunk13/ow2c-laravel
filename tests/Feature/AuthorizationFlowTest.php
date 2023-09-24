<?php

namespace Tests\Feature;

use App\Models\User;
use Laravel\Socialite\Two\User as SocialiteUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
    public function test_successful_oauth_flow()
    {
        // 1. Mock the HTTP call to Battle.net:
        Http::fake([
            'battle.net/oauth/authorize' => Http::response([], 200),
            'battle.net/oauth/token' => Http::response([
                "token_type" => "bearer",
                "expires_in" => 86399,
                "access_token" => "USVpe3EizBul8AM6miqEmDVaR8q1PdWwio",
                "sub" => "388652712",
            ], 200),
            'battle.net/oauth/userinfo' => Http::response([
                'sub' => '388652712',
                'id' => 388652712,
                'battletag' => 'BathtubFarts#1297',
            ], 200),
        ]);


        // 2. Mocking Socialite's BattleNetProvider
        $mockedUser = new SocialiteUser();
        $mockedUser->id = 388652712;
        $mockedUser->nickname = null;
        $mockedUser->name = "BathtubFarts#1297";
        $mockedUser->email = null;
        $mockedUser->avatar = null;
        $mockedUser->user = [
            "sub" => "388652712",
            "id" => 388652712,
            "battletag" => "BathtubFarts#1297",
        ];

        $mockedUser->attributes = [
            "id" => 388652712,
            "name" => "BathtubFarts#1297",
            "sub" => 388652712,
        ];

        $mockedProvider = Mockery::mock(BattleNetProvider::class)->makePartial();
        $mockedProvider->shouldReceive('redirect')->andReturn(redirect('oauth.battle.net/oauth/authorize'));
        $mockedProvider->shouldReceive('user')->andReturn($mockedUser);
        Socialite::shouldReceive('driver')->with('battle_net')->andReturn($mockedProvider);

        // 3. Initiate the OAuth flow by visiting the appropriate route:
        $response = $this->get('/api/battlenet/login');
        $response->assertRedirect('oauth.battle.net/oauth/authorize');
        $response = $this->get('/api/battlenet/callback?code=some_mocked_code');
        $response->assertRedirect('http://localhost:3000/');
        $this->assertDatabaseHas('users', [
            'sub' => '388652712',
            'name' => 'BathtubFarts#1297',
        ]);

        $authenticatedUser = User::where('sub', $mockedUser->attributes['sub'])->first();
        $this->assertAuthenticatedAs($authenticatedUser);
    }
}
