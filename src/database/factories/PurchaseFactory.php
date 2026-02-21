<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'amount'               => fake()->numberBetween(100, 100000),
            'shipping_postal_code' => '123-4567',
            'shipping_address'     => fake()->address(),
            'shipping_building'    => fake()->secondaryAddress(),
            'payment_method'       => 1,
        ];
    }

    public function configure(): static
    {
        return $this
            ->for(Item::factory(), 'item')
            ->for(User::factory(), 'buyer');
    }
}
