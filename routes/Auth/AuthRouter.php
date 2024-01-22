<?php

namespace Routes\Auth;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LogoutController;
use Illuminate\Http\Request;

class AuthRouter
{
    public function __invoke()
    {
        Route::middleware(['auth:sanctum'])->group(function () {
            Route::post('/logout', [LogoutController::class, '__invoke'])->name('logout');

            Route::get('/auth/check', function () {
                return response()->json(['authenticated' => true]);
            });

            Route::get('/user', function (Request $request) {
                return response()->json($request->user());
            });
        });
    }
}