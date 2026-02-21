<?php


namespace Database\Factories;

use App\Models\User;
use App\Models\Condition;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{

    public function definition(): array
    {
        return [
            'seller_id'    => User::factory(),
            'title'        => fake()->sentence(3),
            'description'  => fake()->paragraph(),
            'price'        => fake()->numberBetween(100, 100000),
            'brand_name'   => fake()->company(),
            'image_url'    => fake()->imageUrl(),
            'condition_id' => 1,
        ];
    }
}
