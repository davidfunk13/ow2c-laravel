<?php

namespace Routes\Map;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Map\IndexController;

class MapRouter
{
    public function __invoke()
    {
        Route::middleware(['auth:sanctum'])->group(function () {
            Route::prefix('maps')->group(function () {
                Route::get('/', [IndexController::class, 'index'])->name('api.maps.index');
            });
        });
    }
}