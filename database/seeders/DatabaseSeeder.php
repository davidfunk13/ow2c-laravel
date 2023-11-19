<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {



        $this->call([
            HeroSeeder::class,
            MapSeeder::class,
        ]);

        if (config('app.env') === 'local' && $this->command->confirm('Do you want to seed games?')) {

            if (!env('GAME_SEED_USERNAME') || !env('GAME_SEED_SUB')) {
                $this->command->error(
                    "GAME_SEED_USERNAME and GAME_SEED_SUB must be set in your .env file to seed games.\n" .
                        "Set these to your actual battletag with numbers in Battle.net format, and your sub id from Battle.net.\n" .
                        "This is so when you log in in Dev, you can have some games."
                );

                return;
            }

            User::factory()->create([
                'name' => env('GAME_SEED_USERNAME'),
                'sub' => (int) env('GAME_SEED_SUB'),
            ]);

            $this->call(GameSeeder::class);
        }
    }
}
