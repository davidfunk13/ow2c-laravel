<?php

namespace App\Repositories\Hero;

use App\Models\OverwatchHero;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;

class HeroRepository extends BaseRepository
{
    public function getAll(): Collection
    {
        $maps = OverwatchHero::get();

        return $maps->isEmpty() ? null : $maps;
    }
    public function getByHeroType(string $heroType)
    {
        $heroType = ucfirst($heroType);
        $heroes = OverwatchHero::where('type', $heroType)->get();

        return $heroes->isEmpty() ? null : $heroes;
    }
}