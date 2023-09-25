<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    // invoke controller

    public function __invoke(Request $request)
    {
        try {
            Auth::guard('web')->logout();
            // Optional: If you want to invalidate the user's session
            $request->session()->invalidate();

            // Optional: If you want to regenerate the session ID to avoid session fixation attacks
            $request->session()->regenerateToken();


            return response()->json(['message' => 'Logged out']);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
