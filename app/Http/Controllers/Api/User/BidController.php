<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\BidResource;
use App\Models\Bid;
use Illuminate\Http\Request;

class BidController extends Controller
{
    /**
     * List all bids by authenticated user
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        $bids = Bid::where('user_id', auth()->id())
            ->with(['auction.category', 'auction.images', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return BidResource::collection($bids)->additional([
            'success' => true,
        ]);
    }
}
