<?php
namespace Database\Seeders;

use App\Models\Game;
use Illuminate\Database\Seeder;
use App\Models\User;
use Database\Factories\GameFactory;
use Illuminate\Support\Facades\Log;

class GameSeeder extends Seeder
{
    /**
     * Run the seeder.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('name', 'BathtubFarts#1297')->first();

        if (!$user) {
            $this->command->info("User BathtubFarts#1297 not found. Skipping seeding games.");
            return;
        }

        $numberOfGames = 100;

        Game::factory()
        ->count($numberOfGames)
        ->create(['user_id' => $user->id]);


        $this->command->info("Seeded {$numberOfGames} games for user {$user->id}");
    }

}
