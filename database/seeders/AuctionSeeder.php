<?php

namespace Database\Seeders;

use App\Models\Auction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class AuctionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Clear old data to prevent duplication
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('bids')->truncate();
        DB::table('watchlists')->truncate();
        DB::table('auction_images')->truncate();
        Auction::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Fetch required relationships
        $users = User::all();
        $categories = \App\Models\Category::active()->get();

        if ($users->isEmpty() || $categories->isEmpty()) {
            return;
        }

        $faker = Faker::create();

        $images = [
            'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?auto=format&fit=crop&w=800',
            'https://images.unsplash.com/photo-1524338198850-8a2ff63aaceb?auto=format&fit=crop&w=800',
            'https://images.unsplash.com/photo-1583121274602-3e2820c69888?auto=format&fit=crop&w=800',
            'https://images.unsplash.com/photo-1535633302743-20914fd267d5?auto=format&fit=crop&w=800',
            'https://images.unsplash.com/photo-1579783902614-a3fb3927b6a5?auto=format&fit=crop&w=800',
            'https://images.unsplash.com/photo-1513584684374-8bdb74ea9ce1?auto=format&fit=crop&w=800'
        ];

        // 3. Generate exactly 100 auctions with different timeframes and statuses
        for ($i = 1; $i <= 100; $i++) {
            
            // Mix: 40 Live, 30 Closed/Past, 30 Upcoming/Future
            if ($i <= 40) {
                // Live Auctions (Active, started in past, ends in future)
                $status = 'active';
                $start = Carbon::now()->subDays(rand(1, 5));
                $end = Carbon::now()->addDays(rand(2, 12));
            } elseif ($i <= 70) {
                // Past/Closed Auctions (Closed status, ended in past)
                $status = 'closed';
                $start = Carbon::now()->subDays(rand(10, 20));
                $end = Carbon::now()->subDays(rand(1, 8));
            } else {
                // Future/Upcoming Auctions (Active status, starts in future)
                $status = 'active';
                $start = Carbon::now()->addDays(rand(1, 10));
                $end = Carbon::now()->addDays(rand(12, 25));
            }

            $cat = $categories->random();
            $user = $users->random();
            $price = rand(500, 20000);

            $auction = Auction::create([
                'user_id'       => $user->id,
                'category_id'   => $cat->id,
                'title'         => ucwords($faker->catchPhrase) . " #" . rand(100, 9999),
                'description'   => $faker->paragraph(4) . " High value and authenticated quality guarantee. Item is sold as is.",
                'starting_price'=> $price,
                'current_price' => $price + (rand(1, 20) * 50),
                'image'         => $images[array_rand($images)],
                'start_time'    => $start,
                'end_time'      => $end,
                'status'        => $status,
            ]);

            // Add 3 random gallery images
            $galleryKeys = array_rand($images, 3);
            foreach ($galleryKeys as $index => $key) {
                $auction->images()->create([
                    'image_path' => $images[$key],
                    'sort_order' => $index,
                ]);
            }
        }
    }
}
