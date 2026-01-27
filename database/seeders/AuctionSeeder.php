<?php

namespace Database\Seeders;

use App\Models\Auction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class AuctionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@larabids.com')->first();
        $superAdmin = User::where('email', 'superadmin@larabids.com')->first();

        if (!$admin || !$superAdmin) {
            return;
        }

        $watches = \App\Models\Category::where('slug', 'watches')->first();
        $electronics = \App\Models\Category::where('slug', 'electronics')->first();
        $art = \App\Models\Category::where('slug', 'art')->first();

        Auction::create([
            'user_id' => $admin->id,
            'category_id' => $watches->id ?? null,
            'title' => 'Vintage Rolex Submariner Watch',
            'description' => 'A beautiful 1970s Rolex Submariner luxury watch in excellent condition.',
            'starting_price' => 5000.00,
            'current_price' => 5500.00,
            'start_time' => Carbon::now(),
            'end_time' => Carbon::now()->addDays(7),
            'status' => 'active',
        ]);

        Auction::create([
            'user_id' => $superAdmin->id,
            'category_id' => $electronics->id ?? null,
            'title' => 'MacBook Pro M3 Max',
            'description' => 'Brand salesman MacBook Pro with M3 Max chip, 64GB RAM, 2TB SSD.',
            'starting_price' => 3000.00,
            'current_price' => 3200.00,
            'start_time' => Carbon::now()->subDays(1),
            'end_time' => Carbon::now()->addDays(3),
            'status' => 'active',
        ]);

        Auction::create([
            'user_id' => $admin->id,
            'category_id' => $art->id ?? null,
            'title' => 'Antique Persian Rug',
            'description' => 'Hand-woven Persian rug from the early 20th century.',
            'starting_price' => 1200.00,
            'current_price' => 1200.00,
            'start_time' => Carbon::now(),
            'end_time' => Carbon::now()->addDays(10),
            'status' => 'active',
        ]);
    }
}
