<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class Good_workFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => $this->faker->numberBetween(1,100),
            'declaration_id' => $this->faker->numberBetween(1,100)
        ];
    }
}
