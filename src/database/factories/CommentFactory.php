<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'comment_text' => fake()->sentence(),
        ];
    }

    public function configure(): static
    {
        return $this
            ->for(User::factory(), 'user')
            ->for(Item::factory(), 'item');
    }
}
