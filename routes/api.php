<?php
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

use App\Http\Controllers\Game\DestroyController;
use App\Http\Controllers\Game\IndexController;
use App\Http\Controllers\Game\ShowController;
use App\Http\Controllers\Game\StoreController;
use App\Http\Controllers\Game\UpdateController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\UnauthorizedController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::get('/unauthorized', [UnauthorizedController::class, '__invoke'])->name('unauthorized');

$middleware = ['api'];

Route::middleware($middleware)->group(function () {
    // Logout
    Route::post('/logout', LogoutController::class)->name('logout');

    Route::get('/auth/check', function () {
        return response()->json(['authenticated' => true]);
    });
    // User
    Route::get('/user', function (Request $request) {
        Log::info('Token from cookie: ' . $request->cookie('token'));

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