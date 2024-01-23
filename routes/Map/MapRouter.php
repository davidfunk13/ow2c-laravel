<?php

namespace Routes\Map;

use App\Http\Controllers\Map\IndexController;
use Illuminate\Support\Facades\Route;

class MapRouter
{
    public function __invoke()
    {
        Route::middleware(['auth:sanctum'])->group(function () {
            Route::prefix('maps')->group(function () {
                Route::get('/', [IndexController::class, '__invoke'])->name('api.maps.index');
            });
        });
    }
}