<?php

namespace App\Http\Requests\Game;

use AdditionalHeroValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use MapSectionsValidRule;

class StoreRequest extends FormRequest
{
    public function rules()
    {
        return [
            'map_played_id' => 'required_without:map_played|exists:overwatch_maps,id',
            'result' => 'required|integer|in:0,1,2',
            'map_played' => [
                'required_without:map_played_id',
                'string',
                'max:255',
                Rule::exists('overwatch_maps', 'name'),
            ],
            'map_section_1',
            'map_section_2',
            'map_section_3' => ['nullable', 'string', 'max:255', new MapSectionsValidRule()],
            'hero_played' => ['required', 'string', 'max:255',  Rule::exists('overwatch_heroes', 'name')],
            'additional_hero_played_1' => ['nullable', 'string', 'max:255', Rule::exists('overwatch_heroes', 'name')],
            'additional_hero_played_2' => ['nullable', 'string', 'max:255', new AdditionalHeroValidationRule()],
        ];
    }
    public function messages()
    {
        return [
            'map_section_1.*' => 'The section is invalid for the selected map.',
            'map_section_2.*' => 'The section is invalid for the selected map.',
            'map_section_3.*' => 'The section is invalid for the selected map.',
            'additional_hero_played_2.*' => 'The second additional hero is only accepted if the first additional hero is provided.',
        ];
    }
}
