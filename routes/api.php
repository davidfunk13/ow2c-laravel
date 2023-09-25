<?php

use App\Http\Controllers\BattleNetController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\Session\StoreController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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

Route::middleware(['web', 'auth:web'])->get('/user', function (Request $request) {
    return $request->user();
});

//session routes
Route::middleware(['web', 'auth:web'])->prefix('session')->group(function () {
    Route::post('/store', StoreController::class)->name('session.store');
});

Route::get('/unauthorized', function () {
    return response()->json([
        'message' => 'Unauthorized'
    ], JsonResponse::HTTP_UNAUTHORIZED);
})->name('unauthorized');
