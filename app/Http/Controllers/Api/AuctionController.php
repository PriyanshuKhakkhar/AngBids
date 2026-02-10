<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Auction;
use Illuminate\Http\Request;

class AuctionController extends Controller
{
    // Get all auctions
    public function index()
    {
        // Get auctions from database
        $auctions = Auction::all();
        
        // Return as JSON
        return response()->json($auctions);
    }

    // Get single auction
    public function show($id)
    {
        // Find auction by ID
        $auction = Auction::find($id);
        
        // If not found, return error
        if (!$auction) {
            return response()->json([
                'error' => 'Auction not found'
            ], 404);
        }
        
        // Return auction as JSON
        return response()->json($auction);
    }
}
