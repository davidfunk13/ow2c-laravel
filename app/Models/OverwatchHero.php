<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OverwatchHero extends Model
{
    use HasFactory;

    protected $fillable = [];
    protected $appends = ["thumbnail_url"];

    public function getThumbnailUrlAttribute()
    {
        $imageName = Str::slug($this->name, '_') . '.webp';

        return Storage::disk('public')->url("heroes/{$imageName}");
    }
}
