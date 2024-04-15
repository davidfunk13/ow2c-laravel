<?php
namespace Database\Seeders;

use App\Models\Game;
use Illuminate\Database\Seeder;
use App\Models\User;

class GameSeeder extends Seeder
{
    /**
     * Run the seeder.
     *
     * @return void
     */
    public function run()
    {
        $devTag = env('DEV_BATTLETAG');

        if (!$devTag) {
            $this->command->info("DEV_BATTLETAG not found. Skipping seeding games.");
            return;
        }

        $user = User::where('name', $devTag)->first();

        if (!$user) {
            $this->command->info("User $devTag not found. Skipping seeding games.");
            return;
        }

        $numberOfGames = 250;

        Game::factory()
            ->count($numberOfGames)
            ->create(['user_id' => $user->id]);


        $this->command->info("Seeded {$numberOfGames} mixed Quick Play and Competitive games for user {$user->name} sub: {$user->sub}");
    }

}