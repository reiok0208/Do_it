<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RelationshipFactory extends Factory
{
    private static int $sequence = 2;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => 1,
            'following_user_id' => function () { return self::$sequence++; }
        ];
    }
}
