<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class BattleNetController extends Controller
{
    public function redirectToProvider()
    {
        return Socialite::driver('battle_net')->redirect();
    }

    public function handleProviderCallback()
    {
        try {
            $socialiteUser = Socialite::driver('battle_net')->user();

            $user = User::firstOrCreate(['sub' => $socialiteUser->attributes['sub']], ['name' => $socialiteUser->attributes['name']]);

            $token = $user->createToken('access-token')->plainTextToken;

            $cookie = Cookie::make('token', $token, 60, null, null, false, true); // 60 minutes expiration

        } catch (Exception $e) {
            Log::error($e->getMessage());
        }

        return redirect('http://localhost:5174/')->withCookie($cookie);
    }
}
