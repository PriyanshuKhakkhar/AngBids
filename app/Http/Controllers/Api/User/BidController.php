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

        $query = Bid::where('user_id', auth()->id())
            ->whereIn('id', function($query) {
                $query->selectRaw('MAX(id)')
                    ->from('bids')
                    ->where('user_id', auth()->id())
                    ->groupBy('auction_id');
            })
            ->with(['auction.category', 'auction.images', 'user']);

        // Filter by Category
        if ($request->filled('category')) {
            $category = \App\Models\Category::where('slug', $request->category)->first();
            if ($category) {
                $categoryIds = $category->getAllChildIds();
                $query->whereHas('auction.category', function($q) use ($categoryIds) {
                    $q->whereIn('id', $categoryIds);
                });
            }
        }

        // Filter by Status
        if ($request->filled('status')) {
            $status = $request->status;
            $query->whereHas('auction', function($q) use ($status) {
                if ($status === 'live') {
                    $q->where('status', 'active')
                      ->where('start_time', '<=', now())
                      ->where('end_time', '>', now());
                } elseif ($status === 'ended') {
                    $q->where('end_time', '<=', now());
                } elseif ($status !== 'all') {
                    $q->where('status', $status);
                }
            });
        }

        // Date Range
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Keyword Search
        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->whereHas('auction', function($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%");
            });
        }

        // Sort
        $sort = $request->input('sort', 'latest');
        match($sort) {
            'price_asc' => $query->orderBy('amount', 'asc'),
            'price_desc' => $query->orderBy('amount', 'desc'),
            default => $query->orderBy('created_at', 'desc'),
        };

        $bids = $query->paginate($perPage);

        return BidResource::collection($bids)->additional([
            'success' => true,
            'filters_applied' => $request->only(['status', 'category', 'start_date', 'end_date', 'sort', 'q']),
        ]);
    }

    /**
     * List active bids (on ongoing auctions)
     */
    public function active(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        $bids = Bid::where('user_id', auth()->id())
            ->whereHas('auction', function($q) {
                $q->where('status', 'active')
                  ->where('end_time', '>', now());
            })
            ->with(['auction.category', 'auction.images', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        // Calculate winning/losing counts
        $winningCount = 0;
        $losingCount = 0;
        
        foreach ($bids as $bid) {
            if ($bid->isWinning()) {
                $winningCount++;
            } else {
                $losingCount++;
            }
        }

        return BidResource::collection($bids)->additional([
            'success' => true,
            'meta' => [
                'total' => $bids->total(),
                'winning_count' => $winningCount,
                'losing_count' => $losingCount,
            ],
        ]);
    }

    //list won bids
    public function won(Request $request){
        $perPage = $request->input('per_page', 10);

        $bids = Bid::where('user_id', auth()->id())
        ->whereHas('auction', function($q){
            $q->where('end_time', '<=', now())
              ->whereColumn('current_price', 'bids.amount');
        })
        ->with(['auction.category', 'auction.images', 'user'])
        ->orderBy('created_at', 'desc')
        ->paginate($perPage);

        return BidResource::collection($bids)->additional([
            'success' => true,
        ]);
    }

    //lost bids
    public function lost(Request $request){
        $perPage = $request->input('per_page', 10);

        $bids = Bid::where('user_id', auth()->id())
        ->whereHas('auction', function($q){
            $q->where('end_time', '<=', now())
              ->whereColumn('current_price', '!=', 'bids.amount');
        })
        ->whereRaw('amount = (SELECT MAX(amount) FROM bids as b2 WHERE b2.auction_id = bids.auction_id AND b2.user_id = bids.user_id)')
        ->with(['auction.category', 'auction.images', 'user'])
        ->orderBy('created_at', 'desc')
        ->paginate($perPage);

        return BidResource::collection($bids)->additional([
            'success' => true,
        ]);
    }
}
