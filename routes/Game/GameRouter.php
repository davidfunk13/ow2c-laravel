<?php

namespace Routes\Game;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Game\DestroyController;
use App\Http\Controllers\Game\IndexController;
use App\Http\Controllers\Game\ShowController;
use App\Http\Controllers\Game\StoreController;
use App\Http\Controllers\Game\UpdateController;

class GameRouter
{
    public function __invoke()
    {
        Route::middleware(['auth:sanctum'])->group(function () {
            Route::prefix('games')->group(function () {
                Route::get('/', [IndexController::class, '__invoke'])->name('api.games.index');
                Route::get('/{gameId}', [ShowController::class, '__invoke'])->name('api.games.show');
                Route::post('/', [StoreController::class, '__invoke'])->name('api.games.store');
                Route::put('/{gameId}', [UpdateController::class, '__invoke'])->name('api.games.update');
                Route::delete('/{gameId}', [DestroyController::class, '__invoke'])->name('api.games.destroy');
            });
        });
    }
}
