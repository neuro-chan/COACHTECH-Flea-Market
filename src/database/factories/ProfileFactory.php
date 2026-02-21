<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileFactory extends Factory
{
    public function definition(): array
    {
        return [
            'profile_image_url' => fake()->imageUrl(),
            'postal_code'       => '123-4567',
            'address'           => fake()->address(),
            'building'          => fake()->secondaryAddress(),
        ];
    }

    public function configure(): static
    {
        return $this->for(User::factory(), 'user');
    }
}
