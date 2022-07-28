<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class Declaration_commentFactory extends Factory
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
            'declaration_id' => $this->faker->numberBetween(1,100),
            'body' => $this->faker->realText(mt_rand(10,50)),
            'created_at' => $this->faker->dateTimeBetween($startDate = '2022/06/01', $endDate = 'now')
        ];
    }
}
