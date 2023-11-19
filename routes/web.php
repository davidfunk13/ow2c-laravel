<?php

use App\Http\Controllers\BattleNetController;
use App\Http\Controllers\Game\DestroyController;
use App\Http\Controllers\Game\IndexController;
use App\Http\Controllers\Game\ShowController;
use App\Http\Controllers\Game\StoreController;
use App\Http\Controllers\Game\UpdateController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\UnauthorizedController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

$middleware = ['web', 'auth:web'];

// Auth
Route::namespace('Battlenet')->prefix('battlenet')->group(function () {
    Route::get('login', [BattleNetController::class, 'redirectToProvider'])->name('battlenet-provider-redirect');
    Route::get('callback', [BattleNetController::class, 'handleProviderCallback'])->name('battlenet-provider-callback');
});

// Logout
Route::middleware($middleware)->post('/logout', LogoutController::class)->name('logout');

// Unauthorized
Route::get('/unauthorized', [UnauthorizedController::class, '__invoke'])->name('unauthorized');

Route::middleware($middleware)->prefix('api')->group(function () {
    // User
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Games
    Route::prefix('games')->group(function () {
        Route::get('/', [IndexController::class, '__invoke'])->name('api.games.index');
        Route::get('/{gameId}', [ShowController::class, '__invoke'])->name('api.games.show');
        Route::post('/', [StoreController::class, '__invoke'])->name('api.games.store');
        Route::put('/{gameId}', [UpdateController::class, '__invoke'])->name('api.games.update');
        Route::delete('/{gameId}', [DestroyController::class, '__invoke'])->name('api.games.destroy');
    });
});
