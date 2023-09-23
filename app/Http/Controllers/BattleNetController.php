<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
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
            $newUser = User::create([
                'name' => $socialiteUser->attributes['name'],
                'sub' => $socialiteUser->attributes['sub'],
            ]);

            $newUser->save();
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }

        // $token = $user->token;

        // Implement your logic here: log in the user, create an account, etc.

        return redirect('http://localhost:5174/');
    }
}
