<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MapSeeder extends Seeder
{
    public function run()
    {
        $maps = require database_path('data/OverwatchMaps.php');
        $currentDateTime = now();

        foreach ($maps as $map) {
            DB::table('overwatch_maps')->insert(array_merge($map, [
                'created_at' => $currentDateTime,
                'updated_at' => $currentDateTime
            ]));
        }
    }
}
