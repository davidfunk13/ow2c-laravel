<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OverwatchMap extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'area_1',
        'area_2',
        'area_3',
        'country',
    ];
    public function gamesOnMapForCurrentUser()
    {
        return $this->games()
            ->where('user_id', auth()->id())
            ->get();
    }

    public function game()
    {
        return $this->hasOne(Game::class);
    }

    public function games()
    {
        return $this->hasMany(Game::class, 'map_played_id');
    }
}
