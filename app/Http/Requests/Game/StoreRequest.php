<?php

namespace App\Http\Requests\Game;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    // ...

    public function rules()
    {
        // Load Overwatch maps and heroes data
        $maps = require database_path('data/OverwatchMaps.php');
        $mapNames = array_column($maps, 'name');

        $heroes = require database_path('data/OverwatchHeroes.php');
        $heroNames = array_column($heroes, 'name');

        return [
            // 'user_id' => 'required|integer|exists:users,id',
            'result' => 'required|integer|in:0,1,2',
            'map_played' => ['required', 'string', 'max:255', Rule::in($mapNames)],
            'map_section_1' => 'nullable|string|max:255',
            'map_section_2' => 'nullable|string|max:255',
            'map_section_3' => 'nullable|string|max:255',
            'hero_played' => ['required', 'string', 'max:255', Rule::in($heroNames)],
            'additional_hero_played_1' => ['nullable', 'string', 'max:255', Rule::in($heroNames)],
            'additional_hero_played_2' => ['nullable', 'string', 'max:255', Rule::in($heroNames)],
        ];
    }
}
