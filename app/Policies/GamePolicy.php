<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Game;
use Illuminate\Support\Facades\Log;

class GamePolicy
{
    public function create()
    {
        return auth()->check() ? true : false;
    }

    public function update(User $user, Game $game)
    {
        $authorized = Game::where('id', $game->id)->where('user_id', $user->id)->exists();
        return $authorized;
    }

    public function destroy(User $user, int $game_id)
    {
        return Game::where('id', $game_id)->where('user_id', $user->id)->exists();
    }

    public function show(User $user, int $game_id)
    {
        return Game::where('id', $game_id)->where('user_id', $user->id)->exists();
    }

    public function index(User $user)
    {
        return auth()->check() ? true : false;
    }
}