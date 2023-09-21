<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BattleNetController;
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

Route::get('/', function () {
    return view('welcome');
});
Route::get('/login/battlenet', 'App\Http\Controllers\BattleNetController@redirectToProvider');
Route::get('/login/battlenet/callback', 'App\Http\Controllers\BattleNetController@handleProviderCallback');
