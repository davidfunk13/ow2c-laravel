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
        'map_played_id',
        // 'game_mode',
        // 'game_type',
        // 'map_section_1',
        // 'map_section_2',
        // 'map_section_3',
        // 'round_1_outcome',
        // 'round_2_outcome',
        // 'round_3_outcome',
        // 'round_wins',
        // 'round_losses',
        'hero_played_id',
        // 'additional_hero_played_1',
        // 'additional_hero_played_2',
        // 'user_id',
        // 'game_role',
    ];

    protected $appends = [
        'map_played_name',
        'game_mode_name',
        'hero_played_name',
        'result_string',
        'game_role',
        'game_role_id'
    ];

    // Result constants
    const RESULT_WIN = 1;
    const RESULT_LOSS = 0;
    const RESULT_DRAW = 2;
    // Role constants
    const ROLE_TANK = 0;
    const ROLE_DAMAGE = 1;
    const ROLE_SUPPORT = 2;


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
        //TODO: Add this back in as we go.
        static::saving(function ($game) {
            //     // Load the map and hero details
            //     $maps = self::loadMaps();
            //     $heroes = self::loadHeroes();
            //     // Validate the selected map
            //     $currentMap = self::getCurrentMap($game->map_played, $game->map_played_id);

            //     // Validate map sections
            //     self::validateMapSections($currentMap, $game);

            //     // Validate the main hero
            //     $mainHero = self::validateMainHeroName($heroes, $game->hero_played);

            //     //set the game's role from the hero
            //     $game->game_role = $mainHero['type'];

            //     // Validate additional heroes' types to match the main hero's type
            //     self::validateAdditionalHeroes($heroes, $game, $mainHero);

            //     // Set the game type based on the map
            //     self::setGameType($game, $currentMap);
            //     // Update round wins and losses
            //     $game->updateRoundWinsAndLosses();

            //     // Assign map relationships
            //     $game->assignMapIdByName($currentMap->name);
            //     $game->assignMapNameById($currentMap->id);

            $game->assignLoggedInUserId();
        });
    }
    public function getResultStringAttribute()
    {
        $result = $this->result;
        switch ($result) {
            case self::RESULT_WIN:
                return 'Win';
            case self::RESULT_LOSS:
                return "Loss";
            case self::RESULT_DRAW:
                return 'Draw';
            default:
                throw new Exception("Invalid result '{$result}'");
        }
    }

    public function getMapPlayedNameAttribute()
    {
        $map = OverwatchMap::find($this->map_played_id);
        return $map->name;
    }
    public function getHeroPlayedNameAttribute()
    {
        $hero = OverwatchHero::find($this->hero_played_id);
        return $hero->name;
    }

    public function getGameRoleAttribute()
    {
        $hero = OverwatchHero::find($this->hero_played_id);

        switch ($hero->type_id) {
            case self::ROLE_TANK:
                return 'Tank';
            case self::ROLE_DAMAGE:
                return 'Damage';
            case self::ROLE_SUPPORT:
                return 'Support';
            default:
                throw new Exception("Invalid hero type '{$hero->type_id}'");
        }
    }
    public function getGameModeNameAttribute()
    {
        $map = OverwatchMap::find($this->map_played_id);
        return $map->type;
    }

    public function getGameRoleIdAttribute()
    {
        $hero = OverwatchHero::find($this->hero_played_id);

        if (!$hero) {
            return "Something went wrong";
        }

        return $hero->type_id;
    }

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
}