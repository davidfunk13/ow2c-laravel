
<?php

namespace App\Repositories\Map;

use App\Models\OverwatchMap;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;

class MapRepository extends BaseRepository
{
    public function getAll(): Collection
    {
        return OverwatchMap::all();
    }
}
