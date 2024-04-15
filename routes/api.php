<?php
use Routes\Auth\AuthRouter;
use Routes\Game\GameRouter;
use Routes\Hero\HeroRouter;
use Routes\Map\MapRouter;

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

(new AuthRouter())();
(new GameRouter())();
(new HeroRouter())();
(new MapRouter())();