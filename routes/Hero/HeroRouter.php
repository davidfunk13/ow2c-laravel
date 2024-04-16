<?php

namespace Routes\Hero;

use App\Http\Controllers\Hero\IndexController;
use Illuminate\Support\Facades\Route;

class HeroRouter
{
    public function __invoke()
    {
        Route::middleware(['auth:sanctum'])->group(function () {
            Route::prefix('heroes')->group(function () {
                Route::get('/{type?}', [IndexController::class, '__invoke'])->name('api.heroes.index');
            });
        });
    }
}