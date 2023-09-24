<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->post('/logout', function (Request $request) {
    Auth::guard('web')->logout();   // Log the user out of the Laravel's session.

    $request->session()->invalidate();  // Invalidate the session.
    $request->session()->regenerateToken();  // Regenerate CSRF token.

    return response()->json(['message' => 'Logged out']);
});

Route::get('battlenet/login', 'App\Http\Controllers\BattleNetController@redirectToProvider');
Route::get('battlenet/callback', 'App\Http\Controllers\BattleNetController@handleProviderCallback');
