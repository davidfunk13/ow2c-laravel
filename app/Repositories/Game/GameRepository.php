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

    public function update(Game $game, array $options): Game
    {
        $this->setFields($game, $options);
        $game->save();

        return $game;
    }

    public function destroy(Game $game): void
    {
        $game->delete();
    }

    protected function setFields(Game &$game, array $options): void
    {
        $game->user_id = $options['user']['id'] ?? null;
        $game->result = $options['result'];
        $game->game_role = $options['game_role'] ?? null;
        $game->game_mode = $options['game_mode'] ?? null;
        $game->game_type = $options['game_type'] ?? null;
        $game->hero_played = $options['hero_played'];
        $game->map_played = $options['map_played'] ?? null;
        $game->map_played_id = $options['map_played_id'] ?? null;
        // $game->round_losses = $options['round_losses'] ?? 0;
        $game->round_1_outcome = $options['round_1_outcome'] ?? null;
        $game->round_2_outcome = $options['round_2_outcome'] ?? null;
        $game->round_3_outcome = $options['round_3_outcome'] ?? null;
        // $game->round_wins = $options['round_wins'] ?? 0;
        $game->map_section_1 = $options['map_section_1'] ?? null;
        $game->map_section_2 = $options['map_section_2'] ?? null;
        $game->map_section_3 = $options['map_section_3'] ?? null;
        $game->additional_hero_played_1 = $options['additional_hero_played_1'] ?? null;
        $game->additional_hero_played_2 = $options['additional_hero_played_2'] ?? null;
    }
}
