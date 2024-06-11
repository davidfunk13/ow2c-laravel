<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class BattleNetController extends Controller
{
    public function redirectToProvider()
    {
        Log::info("hit");

        return Socialite::driver('battle_net')->redirect();
    }

    public function handleProviderCallback(Request $request)
    {
        try {
            $socialiteUser = Socialite::driver('battle_net')->user();

            $user = User::firstOrCreate(['sub' => $socialiteUser->attributes['sub']], ['name' => $socialiteUser->attributes['name']]);
            Auth::login($user);

            session()->regenerate();

            $battleNetCallbackFeUri = config('services.battle_net.callback_fe_uri');
            Log::info($battleNetCallbackFeUri);
            Log::info($request->all());
            Log::info($user->toArray());
            //attach the user as params to the uri
            //stringify user object and attach as one param
            $battleNetCallbackFeUri = $battleNetCallbackFeUri . '?' . http_build_query($user->toArray());
            return redirect($battleNetCallbackFeUri);

        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => $e->getTraceAsString()], 500);
        }
    }
}
