<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OverwatchMap extends Model
{
    use HasFactory;
    const CONTROL = "Control";
    const ESCORT = "Escort";
    const PUSH = "Push";
    const HYBRID = "Hybrid";
    const FLASHPOINT = "Flashpoint";
    protected $fillable = [
        'name',
        'type',
        'area_1',
        'area_2',
        'area_3',
        'country',
    ];

    protected $appends =["thumbnail_url"];

    public function getThumbnailUrlAttribute()
    {
        $imageName = Str::slug($this->name, '_').'.webp';

        return Storage::disk('public')->url("maps/{$imageName}");
    }
    
    public function game()
    {
        return $this->hasOne(Game::class);
    }
    public function getType(string $type)
    {
        return $this->where('type', $type)->get();
    }
    public function games()
    {
        return $this->hasMany(Game::class, 'map_played_id');
    }
    public function hasSections()
    {
        // TODO: Replace 'Control' with whatever condition determines if a map has sections
        
        return $this->type === 'Control';
    }
}
