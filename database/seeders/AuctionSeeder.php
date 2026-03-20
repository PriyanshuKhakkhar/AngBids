<?php

namespace Database\Seeders;

use App\Models\Auction;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

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
        DB::table('auction_registrations')->truncate();
        Auction::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Fetch required relationships
        $users = User::all();
        $categories = Category::where('is_active', true)->get();

        if ($users->isEmpty() || $categories->isEmpty()) {
            return;
        }

        // 🟢 PRE-VERIFIED WORKING IMAGES (Only from stable sources like Unsplash)
        $validImages = [
            'https://images.unsplash.com/photo-1523275335684-37898b6baf30?q=80&w=800&auto=format&fit=crop', // Watch
            'https://images.unsplash.com/photo-1542496658-e33a6d0d50f6?q=80&w=800&auto=format&fit=crop', // Watch 2
            'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?q=80&w=800&auto=format&fit=crop', // Laptop
            'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?q=80&w=800&auto=format&fit=crop', // Headphones
            'https://images.unsplash.com/photo-1583121274602-3e2820c69888?q=80&w=800&auto=format&fit=crop', // Red Car
            'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?q=80&w=800&auto=format&fit=crop', // Sports Car
            'https://images.unsplash.com/photo-1511193311914-0346f16efe90?q=80&w=800&auto=format&fit=crop', // Art
            'https://images.unsplash.com/photo-1579783902614-a3fb3927b6a5?q=80&w=800&auto=format&fit=crop', // Modern Art
            'https://images.unsplash.com/photo-1511919884226-fd3cad34687c?q=80&w=800&auto=format&fit=crop', // Motorcycle
            'https://images.unsplash.com/photo-1508685096489-7aac2914b2b8?q=80&w=800&auto=format&fit=crop', // Classic Watch
            'https://images.unsplash.com/photo-1524338198850-8a2ff63aaceb?q=80&w=800&auto=format&fit=crop', // Camera
            'https://images.unsplash.com/photo-1533038590840-1cde6b66b7c6?q=80&w=800&auto=format&fit=crop', // Antique
            'https://images.unsplash.com/photo-1526170375885-4d8ecbc01831?q=80&w=800&auto=format&fit=crop', // Polaroid
            'https://images.unsplash.com/photo-1544117518-3baf3525d8b9?q=80&w=800&auto=format&fit=crop', // Bag
            'https://images.unsplash.com/photo-1511285560929-80b456fea0bc?q=80&w=800&auto=format&fit=crop', // Art 2
            'https://images.unsplash.com/photo-1531297484001-80022131f5a1?q=80&w=800&auto=format&fit=crop', // Tech
        ];

        $titlesByCat = [
            'Electronics' => ['Apple Macbook Pro 16"', 'Sony WH-1000XM5 Headphones', 'Nikon Z9 Camera', 'iPhone 15 Pro Max', 'Dell XPS 17 Laptop'],
            'Vehicles' => ['Vintage Mustang 1967', 'Tesla Model S Plaid', 'Harley Davidson Fat Bob', 'Audi RS7 Sportback', 'Ducati Panigale V4'],
            'Watches' => ['Rolex Submariner Date', 'Omega Speedmaster Moonwatch', 'Patek Philippe Nautilus', 'Tag Heuer Monaco', 'Hublot Big Bang'],
            'Art & Antiques' => ['Original Oil Painting', 'Bronze Statue of Thinker', 'Ancient Roman Coins Set', 'Signed Picasso Sketch', 'Ming Dynasty Vase'],
        ];

        // 3. Generate 60 auctions
        for ($i = 1; $i <= 60; $i++) {
            
            $cat = $categories->random();
            $user = $users->random();
            $price = rand(10000, 95000);
            
            $catName = $cat->name;
            $possibleTitles = $titlesByCat[$catName] ?? $titlesByCat['Electronics'];
            $title = $possibleTitles[array_rand($possibleTitles)] . " (Lot #" . rand(1001, 9999) . ")";

            // Status Logic: 20 Live, 20 Upcoming, 20 Closed
            if ($i <= 20) {
                // LIVE
                $status = 'active';
                $start = Carbon::now()->subHours(rand(1, 48));
                $end = Carbon::now()->addHours(rand(24, 96));
            } elseif ($i <= 40) {
                // UPCOMING
                $status = 'active';
                $start = Carbon::now()->addHours(rand(2, 120));
                $end = Carbon::now()->addHours(rand(140, 240));
            } else {
                // CLOSED
                $status = 'closed';
                $start = Carbon::now()->subDays(rand(10, 20));
                $end = Carbon::now()->subDays(rand(1, 4));
            }

            // High Value Details
            $description = "Exquisite " . $title . " available for auction in the " . $catName . " category. This premium item is part of a private collection and is maintained in pristine condition. Includes full certification, original packaging, and express worldwide delivery. Guaranteed high-value investment opportunity for collectors.";

            // Dynamic Increment
            $minIncrement = 500.00;
            if ($price > 50000) $minIncrement = 1000.00;

            // Pick Image
            $img = $validImages[($i - 1) % count($validImages)];

            $auction = Auction::create([
                'user_id'       => $user->id,
                'category_id'   => $cat->id,
                'title'         => $title,
                'description'   => $description,
                'starting_price'=> $price,
                'current_price' => $status === 'closed' ? $price + rand(20000, 50000) : $price,
                'image'         => $img,
                'start_time'    => $start,
                'end_time'      => $end,
                'status'        => $status,
                'min_increment' => $minIncrement,
                'winner_id'     => $status === 'closed' ? $users->where('id', '!=', $user->id)->random()->id : null
            ]);

            // Add 3 high-quality gallery images
            $galleryItems = array_rand($validImages, 3);
            foreach ($galleryItems as $idx => $key) {
                $auction->images()->create([
                    'image_path' => $validImages[$key],
                    'sort_order' => $idx,
                ]);
            }
        }
    }
}
