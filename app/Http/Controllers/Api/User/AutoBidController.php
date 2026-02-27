<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\AutoBidResource;
use App\Models\AutoBids;
use App\Models\Auction;
use Illuminate\Http\Request;
use Exception;

class AutoBidController extends Controller
{
    /**
     * Display a listing of the authenticated user's auto bids.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        
        $autoBids = AutoBids::where('user_id', auth()->id())
            ->with(['auction.category', 'auction.images'])
            ->latest()
            ->paginate($perPage);

        return AutoBidResource::collection($autoBids)->additional([
            'success' => true
        ]);
    }

    /**
     * Store or update an auto bid.
     */
    public function store(Request $request)
    {
        $request->validate([
            'auction_id' => 'required|exists:auctions,id',
            'max_bid_amount' => 'required|numeric|min:0',
        ]);

        $auction = Auction::findOrFail($request->auction_id);
        
        try {
            $bidService = app(\App\Services\BidService::class);
            
            $result = $bidService->placeBid($auction, [
                'max_bid_amount' => $request->max_bid_amount
            ], auth()->user());

            $autoBid = AutoBids::where('user_id', auth()->id())
                ->where('auction_id', $auction->id)
                ->first();

            return (new AutoBidResource($autoBid))->additional([
                'success' => true,
                'message' => 'Auto bid set successfully',
                'bid_result' => $result
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Deactivate an auto bid.
     */
    public function destroy(string $id)
    {
        $autoBid = AutoBids::where('user_id', auth()->id())
            ->where('id', $id)
            ->firstOrFail();

        $autoBid->update(['active' => false]);

        return response()->json([
            'success' => true,
            'message' => 'Auto bid deactivated successfully'
        ]);
    }
}
