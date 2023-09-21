<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class BattleNetController extends Controller
{
    public function redirectToProvider()
    {dump('yo');
        return Socialite::driver('battle_net')->redirect();
    }

    public function handleProviderCallback()
    {
        $user = Socialite::driver('battle_net')->user();
        // $token = $user->token;

        // Implement your logic here: log in the user, create an account, etc.

        return redirect()->route('home');
    }
}
