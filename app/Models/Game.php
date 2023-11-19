<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Exception;

class Game extends Model
{
    use HasFactory;

    // Fillable attributes
    protected $fillable = [
        'result',
        'map_played',
        'game_mode',
        'game_type',
        'map_section_1',
        'map_section_2',
        'map_section_3',
        'round_1_outcome',
        'round_2_outcome',
        'round_3_outcome',
        'round_wins',
        'round_losses',
        'hero_played',
        'additional_hero_played_1',
        'additional_hero_played_2',
        'user_id',
        'game_role',
    ];

    // Result constants
    const RESULT_WIN = 0;
    const RESULT_LOSS = 1;
    const RESULT_DRAW = 2;

    // Relationships
    public function map()
    {
        return $this->belongsTo(OverwatchMap::class, 'map_played');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Boot function from Laravel.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($game) {
            // Load the map and hero details
            $maps = self::loadMaps();
            $heroes = self::loadHeroes();

            // Validate the selected map
            $currentMap = self::getCurrentMap($maps, $game->map_played);

            // Validate map sections
            self::validateMapSections($currentMap, $game);

            // Validate the main hero
            $mainHero = self::getHeroByName($heroes, $game->hero_played);

            // Validate additional heroes' types to match the main hero's type
            self::validateAdditionalHeroes($heroes, $game, $mainHero);

            // Set the game type based on the map
            self::setGameType($game, $currentMap);

            // Update round wins and losses
            $game->updateRoundWinsAndLosses();

            // Assign map ID by name
            $game->assignMapIdByName($currentMap);
        });
    }

    // Load maps data
    private static function loadMaps()
    {
        return require database_path('data/OverwatchMaps.php');
    }

    // Load heroes data
    private static function loadHeroes()
    {
        return require database_path('data/OverwatchHeroes.php');
    }

    // Get the current map by name
    private static function getCurrentMap($maps, $mapName)
    {
        $currentMap = collect($maps)->firstWhere('name', $mapName);

        if (!$currentMap) {
            throw new Exception("The selected map '{$mapName}' does not exist.");
        }

        return $currentMap;
    }

    // Validate map sections
    private static function validateMapSections($currentMap, $game)
    {
        foreach (['map_section_1', 'map_section_2', 'map_section_3'] as $section) {
            if ($game->$section && !in_array($game->$section, [$currentMap['area_1'], $currentMap['area_2'], $currentMap['area_3']])) {
                throw new Exception("The section '{$game->$section}' is invalid for the map '{$game->map_played}'.");
            }
        }
    }

    // Get hero by name
    private static function getHeroByName($heroes, $heroName)
    {
        $hero = collect($heroes)->firstWhere('name', $heroName);

        if (!$hero) {
            throw new Exception("The main hero '{$heroName}' does not exist.");
        }

        return $hero;
    }

    // Validate additional heroes
    private static function validateAdditionalHeroes($heroes, $game, $mainHero)
    {
        foreach (['additional_hero_played_1', 'additional_hero_played_2'] as $additionalHeroField) {
            $additionalHeroName = $game->$additionalHeroField;
            if ($additionalHeroName) {
                $additionalHero = self::getHeroByName($heroes, $additionalHeroName);
                if ($additionalHero['type'] !== $mainHero['type']) {
                    throw new Exception("The additional hero '{$additionalHeroName}' must be of type '{$mainHero['type']}' to match the main hero '{$game->hero_played}'.");
                }
            }
        }
    }

    // Set game type based on the map
    private static function setGameType($game, $currentMap)
    {
        $game->game_type = $currentMap['type']; // Assuming the map data array has a 'type' field
    }

    // Update round wins and losses based on round outcomes
    protected function updateRoundWinsAndLosses()
    {
        $wins = 0;
        $losses = 0;

        foreach (['round_1_outcome', 'round_2_outcome', 'round_3_outcome'] as $round) {
            if ($this->$round === 1) {
                $wins++;
            } elseif ($this->$round === 0) {
                $losses++;
            }
        }

        $this->round_wins = $wins;
        $this->round_losses = $losses;
    }

    // Assign map ID by name
    public function assignMapIdByName($mapName)
    {
        // Find the map with the given name
        $map = OverwatchMap::where('name', $mapName)->first();

        if (!$map) {
            throw new Exception("The selected map '{$mapName}' does not exist.");
        }

        // Assign the map ID to the game model
        $this->map_played_id = $map->id;
    }
}
