<?php

namespace App\Repositories\Map;

use App\Models\OverwatchMap;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;

class MapRepository extends BaseRepository
{
    /**
     * Get all Overwatch maps by game type.
     *
     * @return Collection|OverwatchMap[]|null
     */
    public function getAll(): Collection
    {
        $maps = OverwatchMap::get();

        return $maps->isEmpty() ? null : $maps;
    }

    /**
     * Get all Overwatch maps by game type.
     *
     * @param  string  $gameType
     * @return Collection|OverwatchMap[]|null
     */
    public function getByGameType(string $gameType)
    {
        $gameType = ucfirst($gameType);
        $maps = OverwatchMap::where('type', $gameType)->get();

        return $maps->isEmpty() ? null : $maps;
    }
}
