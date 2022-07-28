<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class Declaration_tagFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'declaration_id' => $this->faker->numberBetween(1,100),
            'tag_id' => $this->faker->numberBetween(1,50)
        ];
    }
}
