<?php

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class MapSectionsValidRule implements ValidationRule
{
    public function validate(string $attribute, $value, Closure $fail): void
    {
        $mapName = request('map_played');

        $hasSection = DB::table('overwatch_maps')
            ->where('name', $mapName)
            ->whereIn($value, ['area_1', 'area_2', 'area_3'])
            ->exists();

        if (!$hasSection) {
            $fail("The selected map section is invalid for the map '{$mapName}'.");
        }
    }
}
