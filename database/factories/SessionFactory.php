<?php

namespace Database\Factories;

use App\Models\Session;
use Illuminate\Database\Eloquent\Factories\Factory;

class SessionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Session::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $ranks= ['Bronze', 'Silver', 'Gold', 'Platinum', 'Diamond', 'Master', 'Grandmaster', 'Top 500'];
    public function definition()
    {

        return [
            'starting_rank' => $this->faker->randomElement($this->ranks),
            'rank' => $this->faker->randomElement($this->ranks),
            'starting_division' => $this->faker->numberBetween(1, 5),
            'division' => $this->faker->numberBetween(1, 5),
        ];

    }
     /**
     * Indicate that the session and division should be null.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function newSession()
    {
        return $this->state([
            'rank' => null,
            'division' => null,
        ]);
    }

    public function mixedSession()
    {
        $fakerInstance =$this->faker->optional(0.5, null);

        return [
            'starting_rank' => $this->faker->randomElement($this->ranks),
            'rank' => $fakerInstance->randomElement($this->ranks),  // 50% chance to be null
            'starting_division' => $this->faker->numberBetween(1, 5),
            'division' => $fakerInstance->numberBetween(1, 5),  // 50% chance to be null
        ];

    }
}
