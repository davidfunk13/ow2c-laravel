<?php

namespace App\Http\Requests\Game;

use App\Models\OverwatchMap;
use App\Rules\AdditionalHeroValidationRule;
use App\Rules\MapSectionsValidRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    public function rules()
    {
        return [
            'map_played_id' => [
                'required_without:map_played',
                'integer',
                Rule::exists('overwatch_maps', 'id')
            ],
            'hero_played_id' => [
                // 'required_without:hero_played',
                'integer',
                Rule::exists('overwatch_heroes', 'id'),
            ],
            'result' => 'required|integer|in:0,1,2',
            // TODO: We'll add these as we go! :)
            // 'map_played' => [
            //     'required_without:map_played_id',
            //     'string',
            //     'max:255',
            //     Rule::exists('overwatch_maps', 'name'),
            // ],
            // 'game_mode' => 'required|integer|in:0,1',
            // 'map_section_1' => 'nullable|string|max:255',
            // 'map_section_2' => 'nullable|string|max:255',
            // 'map_section_3' => 'nullable|string|max:255',
            // 'hero_played' => [
            //     'required_without:hero_played_id',
            //     'string',
            //     'max:255',
            //     Rule::exists('overwatch_heroes', 'name'),
            // ],
            // 'additional_hero_played_1' => ['nullable', 'string', 'max:255', Rule::exists('overwatch_heroes', 'name')],
            // 'additional_hero_played_2' => ['nullable', 'string', 'max:255', new AdditionalHeroValidationRule()],
        ];
    }
    // TODO: Re-enable this! :)
    // protected function withValidator($validator)
    // {
    //     $validator->after(function ($validator) {
    //         $mapPlayedId = $this->input('map_played_id') ?? $this->input('map_played');
    //         $map = OverwatchMap::find($mapPlayedId); // Assuming OverwatchMap is your Eloquent model

    //         if ($map && $map->hasSections()) { // Assuming hasSections() is a method that checks if map has sections
    //             // Apply the MapSectionsValidRule only if the map has sections
    //             $validator->addRules([
    //                 'map_section_1' => ['string', 'max:255', new MapSectionsValidRule()],
    //                 'map_section_2' => ['string', 'max:255', new MapSectionsValidRule()],
    //                 'map_section_3' => ['string', 'max:255', new MapSectionsValidRule()],
    //             ]);
    //         }
    //     });
    // }

    // TODO: Re-enable this! :)
    // public function messages()
    // {
    //     return [
    //         'map_section_1.*' => 'The section is invalid for the selected map.',
    //         'map_section_2.*' => 'The section is invalid for the selected map.',
    //         'map_section_3.*' => 'The section is invalid for the selected map.',
    //         'additional_hero_played_2.*' => 'The second additional hero is only accepted if the first additional hero is provided.',
    //     ];
    // }
}