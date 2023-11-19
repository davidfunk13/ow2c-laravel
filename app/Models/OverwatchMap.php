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
    public function game()
    {
        return $this->hasOne(Game::class);
    }
}
