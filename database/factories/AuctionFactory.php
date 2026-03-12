<?php

namespace Database\Factories;

use App\Models\Auction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuctionFactory extends Factory
{
    protected $model = Auction::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'starting_price' => 1000,
            'current_price' => 1000,
            'start_time' => now()->subDay(),
            'end_time' => now()->addDays(7),
            'status' => 'active',
        ];
    }
}
