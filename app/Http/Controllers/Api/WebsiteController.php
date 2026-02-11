<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Auction;
use App\Http\Resources\AuctionResource;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    /**
     * Get home page data
     * Returns latest 3 active auctions with user, category, and images
     */
    public function index()
    {
        $auctions = Auction::active()
            ->latestFirst()
            ->take(3)
            ->with(['user', 'category', 'images'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'auctions' => AuctionResource::collection($auctions)
            ]
        ]);
    }
}
