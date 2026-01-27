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

        $categories = \App\Models\Category::all()->keyBy('slug');

        $auctions = [
            // Watches
            [
                'category_slug' => 'watches',
                'title' => 'Vintage Rolex Submariner Watch',
                'description' => 'A beautiful 1970s Rolex Submariner luxury watch in excellent condition.',
                'price' => 5000.00,
            ],
            [
                'category_slug' => 'watches',
                'title' => 'Omega Seamaster Aqua Terra',
                'description' => 'Elegant and reliable, the Seamaster is a classic for any collection.',
                'price' => 3500.00,
            ],
            [
                'category_slug' => 'watches',
                'title' => 'Patek Philippe Nautilus',
                'description' => 'The pinnacle of sports luxury watches. Blue dial, stainless steel.',
                'price' => 25000.00,
            ],
            // Electronics
            [
                'category_slug' => 'electronics',
                'title' => 'MacBook Pro M3 Max',
                'description' => 'Top of the line MacBook Pro with 64GB RAM and 2TB SSD.',
                'price' => 3000.00,
            ],
            [
                'category_slug' => 'electronics',
                'title' => 'iPhone 15 Pro Max - 1TB',
                'description' => 'Brand new, sealed. Titanium finish.',
                'price' => 1200.00,
            ],
            [
                'category_slug' => 'electronics',
                'title' => 'Sony A7R V Mirrorless Camera',
                'description' => 'High-resolution full-frame camera for professional photographers.',
                'price' => 3200.00,
            ],
            // Vintage Cars
            [
                'category_slug' => 'vintage-cars',
                'title' => '1967 Mustang Shelby GT500',
                'description' => 'Iconic American muscle car. Fully restored.',
                'price' => 150000.00,
            ],
            [
                'category_slug' => 'vintage-cars',
                'title' => 'Porsche 911 Carrera (1989)',
                'description' => 'Classic air-cooled 911 in Guards Red.',
                'price' => 75000.00,
            ],
            // Jewelry
            [
                'category_slug' => 'jewelry',
                'title' => 'Diamond Engagement Ring 2ct',
                'description' => 'Platinum band with a high-clarity round-cut diamond.',
                'price' => 8000.00,
            ],
            [
                'category_slug' => 'jewelry',
                'title' => 'Emerald Gold Necklace',
                'description' => '18k gold necklace with a large Colombian emerald.',
                'price' => 4500.00,
            ],
            // Art
            [
                'category_slug' => 'art',
                'title' => 'Antique Persian Rug',
                'description' => 'Hand-woven Persian rug from the early 20th century.',
                'price' => 1200.00,
            ],
            [
                'category_slug' => 'art',
                'title' => 'Original Oil Painting - Sunset',
                'description' => 'A stunning landscape oil painting by a rising local artist.',
                'price' => 850.00,
            ],
            [
                'category_slug' => 'art',
                'title' => 'Abstract Bronze Sculpture',
                'description' => 'Modern bronze outdoor sculpture, signed by the artist.',
                'price' => 2100.00,
            ],
            [
                'category_slug' => 'art',
                'title' => 'Vintage Movie Poster - Casablanca',
                'description' => 'Rare original 1942 theatrical release poster.',
                'price' => 500.00,
            ],
            [
                'category_slug' => 'art',
                'title' => 'Ceramic Vase (Ming Dynasty Style)',
                'description' => 'Beautifully crafted porcelain vase with traditional patterns.',
                'price' => 3000.00,
            ],
        ];

        $categoryImages = [
            'watches' => 'https://images.unsplash.com/photo-1524338198850-8a2ff63aaceb?auto=format&fit=crop&w=800',
            'electronics' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?auto=format&fit=crop&w=800',
            'vintage-cars' => 'https://images.unsplash.com/photo-1583121274602-3e2820c69888?auto=format&fit=crop&w=800',
            'jewelry' => 'https://images.unsplash.com/photo-1535633302743-20914fd267d5?auto=format&fit=crop&w=800',
            'art' => 'https://images.unsplash.com/photo-1579783902614-a3fb3927b6a5?auto=format&fit=crop&w=800',
        ];

        foreach ($auctions as $data) {
            Auction::create([
                'user_id' => rand(0, 1) ? $admin->id : $superAdmin->id,
                'category_id' => $categories[$data['category_slug']]->id ?? null,
                'title' => $data['title'],
                'description' => $data['description'],
                'starting_price' => $data['price'],
                'current_price' => $data['price'] + (rand(5, 20) * 10),
                'image' => $categoryImages[$data['category_slug']] ?? null,
                'start_time' => Carbon::now(),
                'end_time' => Carbon::now()->addDays(rand(3, 14)),
                'status' => 'active',
            ]);
        }
    }
}
