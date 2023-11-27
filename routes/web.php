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

$middleware = ['web'];

// Auth
Route::namespace('Battlenet')->prefix('battlenet')->group(function () {
    Route::get('login', [BattleNetController::class, 'redirectToProvider'])->name('battlenet-provider-redirect');
    Route::get('callback', [BattleNetController::class, 'handleProviderCallback'])->name('battlenet-provider-callback');
});


// Unauthorized
Route::get('/unauthorized', [UnauthorizedController::class, '__invoke'])->name('unauthorized');