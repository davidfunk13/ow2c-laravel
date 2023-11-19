<?php

namespace App\Repositories\Game;

use App\Models\Game;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;

class GameRepository extends BaseRepository
{
    public function getAll(): Collection
    {
        return Game::all();
    }

    public function findById(int $id): ?Game
    {
        return Game::find($id);
    }

    public function save(array $data): Game
    {
        $game = new Game();
        $this->setFields($game, $data);
        $game->save();

        return $game;
    }

    public function update(Game $game, array $data): Game
    {
        $this->setFields($game, $data);
        $game->save();

        return $game;
    }

    public function destroy(Game $game): void
    {
        $game->delete();
    }

    protected function setFields(Game &$game, array $options): void
    {
        $game->user_id = $options['user']['id'];
        $game->result = $options['result'];
        $game->map_played = $options['map_played'];

        // Setting optional map sections if provided
        $game->map_section_1 = $options['map_section_1'] ?? null;
        $game->map_section_2 = $options['map_section_2'] ?? null;
        $game->map_section_3 = $options['map_section_3'] ?? null;

        // Hero Played
        $game->hero_played = $options['hero_played'];

        // Optional additional hero played columns
        $game->additional_hero_played_1 = $options['additional_hero_played_1'] ?? null;
        $game->additional_hero_played_2 = $options['additional_hero_played_2'] ?? null;
    }

}
