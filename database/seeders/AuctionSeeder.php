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

        Auction::create([
            'user_id' => $admin->id,
            'title' => 'Vintage Rolex Submariner',
            'description' => 'A beautiful 1970s Rolex Submariner in excellent condition.',
            'starting_price' => 5000.00,
            'current_price' => 5500.00,
            'start_time' => Carbon::now(),
            'end_time' => Carbon::now()->addDays(7),
            'status' => 'active',
        ]);

        Auction::create([
            'user_id' => $superAdmin->id,
            'title' => 'MacBook Pro M3 Max',
            'description' => 'Brand new MacBook Pro with M3 Max chip, 64GB RAM, 2TB SSD.',
            'starting_price' => 3000.00,
            'current_price' => 3200.00,
            'start_time' => Carbon::now()->subDays(1),
            'end_time' => Carbon::now()->addDays(3),
            'status' => 'active',
        ]);

        Auction::create([
            'user_id' => $admin->id,
            'title' => 'Antique Persian Rug',
            'description' => 'Hand-woven Persian rug from the early 20th century.',
            'starting_price' => 1200.00,
            'current_price' => 1200.00,
            'start_time' => Carbon::now()->addDays(2),
            'end_time' => Carbon::now()->addDays(10),
            'status' => 'draft',
        ]);
    }
}
