<?php

namespace Database\Factories;

use App\Models\Declaration;
use Illuminate\Database\Eloquent\Factories\Factory;

class DeclarationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => $this->faker->numberBetween(2,100),
            'title' => $this->faker->realText(mt_rand(10,13)),
            'body' => $this->faker->realText(mt_rand(10,130)),
            'start_date' => $this->faker->dateTimeBetween($startDate = 'now +1 day', $endDate = '+5 day'),
            'end_date' => $this->faker->dateTimeBetween($startDate = 'now  +1 week', $endDate = '+2 week'),
        ];
    }
}
