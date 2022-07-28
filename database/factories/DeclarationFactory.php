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
            'user_id' => $this->faker->numberBetween(1,100),
            'title' => $this->faker->realText(mt_rand(10,15)),
            'body' => $this->faker->realText(mt_rand(10,500)),
            'start_date' => $this->faker->dateTimeBetween($startDate = '2022/06/01', $endDate = '2022/07/01'),
            'end_date' => $this->faker->dateTimeBetween($startDate = 'now -2 day', $endDate = '+2 week'),
        ];
    }
}
