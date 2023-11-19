<?php

namespace Database\Factories;

use App\Models\Game;
use App\Models\OverwatchHero;
use App\Models\OverwatchMap;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Log;

class GameFactory extends Factory
{
    protected $model = Game::class;
    private $userId = null;

    public function withUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    public function definition(): array
    {

        // Select a random map and a hero
        $map = OverwatchMap::inRandomOrder()->first();
        $hero = OverwatchHero::inRandomOrder()->first();

        // Filter valid map sections, shuffle, and take up to three
        $mapSections = collect(array_filter([$map['area_1'] ?? null, $map['area_2'] ?? null, $map['area_3'] ?? null]))
            ->shuffle()
            ->take(3);

        // Select heroes of the same type, exclude the main hero, shuffle, and take two
        $heroesOfSameType = OverwatchHero::where('type', $hero['type'])
        ->where('name', '!=', $hero['name'])
        ->inRandomOrder()
        ->take(2)
        ->get();

        // randomize game_mode
        $gameMode = $this->faker->randomElement([0, 1]);

        // Determine round outcomes
        $roundOutcomes = [
            'round_1_outcome' => $mapSections->get(0) ? $this->faker->numberBetween(0, 1) : null,
            'round_2_outcome' => $mapSections->get(1) ? $this->faker->numberBetween(0, 1) : null,
            'round_3_outcome' => $mapSections->get(2) ? $this->faker->numberBetween(0, 1) : null,
        ];

        // Determine whether additional_hero_played_1 and 2 should be null
        $additionalHero1 = $this->faker->optional()->randomElement([$heroesOfSameType->get(0)['name'], null]);
        $additionalHero2 = $additionalHero1 ? $this->faker->optional()->randomElement([$heroesOfSameType->get(1)['name'], null]) : null;

        // Create the return array with conditional inclusion of round outcomes and additional_hero_played_1 and 2
        $returnArray = [
            'result' => $this->faker->randomElement([0, 1, 2]),
            'map_played' => $map['name'],
            'map_section_1' => $mapSections->get(0),
            'map_section_2' => $mapSections->get(1),
            'map_section_3' => $mapSections->get(2),
            'hero_played' => $hero['name'],
            'user_id' => $this->userId,
            'game_role' => $hero['type'],
            'game_mode' => $gameMode,
        ];

        // Include round outcomes only if they are not null
        foreach ($roundOutcomes as $key => $value) {
            if ($value !== null) {
                $returnArray[$key] = $value;
            }
        }

        // Include additional_hero_played_1 and 2 with null check
        if ($additionalHero1 !== null) {
            $returnArray['additional_hero_played_1'] = $additionalHero1;
            if ($additionalHero2 !== null) {
                $returnArray['additional_hero_played_2'] = $additionalHero2;
            }
        }

        return $returnArray;
    }
}
