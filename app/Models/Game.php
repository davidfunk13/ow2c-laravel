<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

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
    const RESULT_WIN = 1;
    const RESULT_LOSS = 0;
    const RESULT_DRAW = 2;

    // Relationships
    public function map()
    {
        return $this->belongsTo(OverwatchMap::class, 'map_played_id', 'id');
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
            $currentMap = self::getCurrentMap($game->map_played, $game->map_played_id);

            // Validate map sections
            self::validateMapSections($currentMap, $game);

            // Validate the main hero
            $mainHero = self::validateMainHeroName($heroes, $game->hero_played);

            //set the game's role from the hero
            $game->game_role = $mainHero['type'];

            // Validate additional heroes' types to match the main hero's type
            self::validateAdditionalHeroes($heroes, $game, $mainHero);

            // Set the game type based on the map
            self::setGameType($game, $currentMap);
            // Update round wins and losses
            $game->updateRoundWinsAndLosses();

            // Assign map relationships
            $game->assignMapIdByName($currentMap->name);
            $game->assignMapNameById($currentMap->id);

            $game->assignLoggedInUserId();
        });
    }

    // Get the result as a string
    public function getResultAttribute($value)
    {
        switch ($value) {
            case self::RESULT_WIN:
                return 'Win';
            case self::RESULT_LOSS:
                return 'Loss';
            case self::RESULT_DRAW:
                return 'Draw';
            default:
                throw new Exception("Invalid result '{$value}'");
        }
    }
    // Assign userID FK
    public function assignLoggedInUserId()
    {
        // Check if not running in console (as in, we're saving from a request, not from me running a command hitting a seeder), and not seeding
        if (!App::runningInConsole() && !app()->bound('seeding')) {
            $user = Auth::user();

            if ($user) {
                $this->user_id = $user->id;
            }
        }
    }

    // Get the game mode as a string
    public function getGameModeAttribute($value)
    {
        return $value == 1 ? 'Competitive' : 'Quick Play';
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

    private static function getCurrentMap($mapName = null, $mapId = null)
    {
        if (!$mapName && !$mapId) {
            throw new Exception("Either map name or map ID must be provided.");
        }

        if ($mapId) {
            $currentMap = OverwatchMap::find($mapId);
        } else {
            $currentMap = OverwatchMap::where('name', $mapName)->first();
        }

        if (!$currentMap) {
            throw new Exception("The selected map with name '{$mapName}' or ID '{$mapId}' was not found.");
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
    private static function validateMainHeroName($heroes, $heroName)
    {
        $hero = collect($heroes)->firstWhere('name', $heroName);

        if (!$hero) {
            throw new Exception("The main hero '{$heroName}' was not found.");
        }

        return $hero;
    }

    // Validate additional heroes columns
    private static function validateAdditionalHeroes($heroes, $game, $mainHero)
    {
        foreach (['additional_hero_played_1', 'additional_hero_played_2'] as $additionalHeroField) {
            $additionalHeroName = $game->$additionalHeroField;
            if ($additionalHeroName) {
                $additionalHero = self::validateMainHeroName($heroes, $additionalHeroName);
                if ($additionalHero['type'] !== $mainHero['type']) {
                    throw new Exception("The additional hero '{$additionalHeroName}' must be of type '{$mainHero['type']}' to match the main hero '{$game->hero_played}'.");
                }
            }
        }
    }

    // Set game type based on the map
    private static function setGameType($game, $currentMap)
    {
        $game->game_type = $currentMap->type;
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

    //if map saved with id, assign name
    public function assignMapIdByName($mapName)
    {
            $map = OverwatchMap::where('name', $mapName)->first();

            if (!$map) {
                throw new Exception("The selected map '{$mapName}' was not found.");
            }

            $this->map_played_id = $map->id;
    }

    //if map saved with name, assign id
    public function assignMapNameById($mapId)
    {
        if (!$this->map_played) {
            $map = OverwatchMap::find($mapId);

            if (!$map) {
                throw new Exception("The selected map with ID '{$mapId}' was not found.");
            }

            $this->map_played = $map->name;
        }
    }
}
