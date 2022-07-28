<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'declaration_id' => $this->faker->unique()->numberBetween(1,100),
            'rate' => $this->faker->numberBetween(1,5),
            'execution' => $this->faker->numberBetween(0,1),
            'body' => $this->faker->realText(mt_rand(10,500)),
            'created_at' => $this->faker->dateTimeBetween($startDate = '2022/06/01', $endDate = 'now'),
            'updated_at' => $this->faker->dateTimeBetween($startDate = 'now', $endDate = '+2 week')
        ];
    }
}
