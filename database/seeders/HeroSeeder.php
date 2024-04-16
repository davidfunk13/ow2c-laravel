<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HeroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $heroes = require database_path('data/OverwatchHeroes.php');
        $currentDateTime = now();

        foreach ($heroes as $hero) {
            DB::table('overwatch_heroes')->insert([
                'name' => $hero['name'],
                'type' => $hero['type'],
                'type_id' => $hero['type_id'],
                'created_at' => $currentDateTime,
                'updated_at' => $currentDateTime
            ]);
        }
    }

}
