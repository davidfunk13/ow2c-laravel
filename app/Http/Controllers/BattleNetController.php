<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
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

            Auth::login($user);
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }

        return redirect('http://localhost:3000/');
    }
}
