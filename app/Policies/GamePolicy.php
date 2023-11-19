<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Game;

class GamePolicy
{
    public function create()
    {
        return auth()->check();
    }

    public function update(User $user, int $game_id)
    {
        return Game::where('id', $game_id)->where('user_id', $user->id)->exists();
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
        return Game::where('user_id', $user->id)->exists();
    }
}
