<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAuctionRequest;
use App\Models\Auction;
use App\Models\User;
use App\Services\AuctionService;
use App\Http\Resources\AuctionResource;
use App\Models\Category;
use Illuminate\Http\Request;

class AuctionController extends Controller
{
    protected $auctionService;

    public function __construct(AuctionService $auctionService)
    {
        $this->auctionService = $auctionService;   // You can inject services here if needed
    }
    public function index(Request $request)
    {
        $auctions = $this->auctionService
            ->getFilteredAuctions($request)
            ->with(['user', 'category']) // Eager load relationships
            ->paginate(10);

        $categories = Category::topLevel()->active()->get();

        $subcategories = collect();
        $parentCategory = null;

        if ($request->has('category')) {
            $currentCategory = category::where('slug', $request->category)->first();

            if ($currentCategory->parent_id) {
                $parentCategory = $currentCategory->parent;

                $subcategories = $currentCategory
                    ->children()
                    ->active()
                    ->get();
            } else {
                $parentCategory = $currentCategory;

                $subcategories = $currentCategory
                    ->children()
                    ->active()
                    ->get();
            }
        }
        return AuctionResource::collection($auctions)->additional([
            'success' => true,
            'categories' => $categories,
            'parent_category' => $parentCategory,
            'subcategories' => $subcategories,
        ]);
    }

    // Get single auction
    public function show($id)
    {
        $auction = $this->auctionService->getAuctionById($id);

        return new AuctionResource($auction);
    }

    public function store(StoreAuctionRequest $request)
    {
        if (auth()->check()) {
            $user = auth()->user();
        } else {
            if (!$request->has('user_id')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication required. Please provide user_id or use API token authentication.'
                ], 401);
            }
            
            $user = User::findOrFail($request->user_id);
        }

        $auction = $this->auctionService->createAuction(
            $request->validated(),
            $user
        );

        return new AuctionResource($auction);
    }

    /**
     * Get all bids for a specific auction
     */
    public function bids($id)
    {
        $auction = Auction::findOrFail($id);

        $bids = $auction->bids()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'auction_id' => $auction->id,
                'auction_title' => $auction->title,
                'bids' => \App\Http\Resources\BidResource::collection($bids)
            ]
        ]);
    }

    /**
     * Place a bid on an auction
     */
    public function placeBid($id, \App\Http\Requests\PlaceBidRequest $request)
    {
        $auction = Auction::findOrFail($id);
        
        try {
            $bidService = app(\App\Services\BidService::class);
            $bid = $bidService->placeBid($auction, $request->validated(), auth()->user());

            return response()->json([
                'success' => true,
                'message' => 'Bid placed successfully!',
                'data' => [
                    'bid' => new \App\Http\Resources\BidResource($bid),
                    'auction' => [
                        'id' => $auction->id,
                        'current_price' => $auction->fresh()->current_price
                    ]
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Search auctions with filters
     */
    public function search(Request $request)
    {
        $auctions = $this->auctionService
            ->getFilteredAuctions($request)
            ->with(['user', 'category', 'images'])
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => AuctionResource::collection($auctions),
            'meta' => [
                'current_page' => $auctions->currentPage(),
                'last_page' => $auctions->lastPage(),
                'per_page' => $auctions->perPage(),
                'total' => $auctions->total()
            ]
        ]);
    }
}
