<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAuctionRequest;
use App\Http\Requests\UpdateAuctionRequest;
use App\Http\Requests\SearchAuctionRequest;
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
            $result = $bidService->placeBid($auction, $request->validated(), auth()->user());

            $message = 'Bid placed successfully!';
            if ($result['is_extended']) {
                $message .= ' This auction has been extended by 5 minutes due to fair play rules.';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'bid' => new \App\Http\Resources\BidResource($result['bid']),
                    'is_extended' => $result['is_extended'],
                    'auction' => [
                        'id' => $auction->id,
                        'current_price' => $auction->fresh()->current_price,
                        'end_time' => $auction->fresh()->end_time->toDateTimeString()
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
     * Search and filter auctions with advanced criteria
     * Public endpoint for Angular frontend
     */
    public function search(SearchAuctionRequest $request)
    {
        $validated = $request->validated();
        $perPage = $validated['per_page'] ?? 10;

        // Get filtered auctions using the service
        $auctions = $this->auctionService
            ->getFilteredAuctions($request, false) // false = don't force activeOnly
            ->with(['user', 'category', 'images'])
            ->paginate($perPage);

        // Get statistics for metadata
        $stats = $this->auctionService->getSearchStatistics($request);

        // Build filters applied object
        $filtersApplied = [];
        
        if ($request->filled('q') || $request->filled('keyword')) {
            $filtersApplied['keyword'] = $request->input('q') ?? $request->input('keyword');
        }
        
        if ($request->filled('category')) {
            $filtersApplied['category'] = $request->input('category');
        }
        
        if ($request->filled('category_id')) {
            $filtersApplied['category_id'] = $request->input('category_id');
        }
        
        if ($request->filled('min_price') || $request->filled('max_price')) {
            $filtersApplied['price_range'] = [
                'min' => $request->input('min_price'),
                'max' => $request->input('max_price'),
            ];
        }
        
        if ($request->filled('status')) {
            $filtersApplied['status'] = $request->input('status');
        }
        
        if ($request->filled('sort')) {
            $filtersApplied['sort'] = $request->input('sort');
        }

        return AuctionResource::collection($auctions)->additional([
            'success' => true,
            'filters_applied' => $filtersApplied,
            'statistics' => $stats,
            'available_filters' => [
                'statuses' => ['active', 'pending', 'closed', 'cancelled', 'past', 'all'],
                'sort_options' => ['latest', 'price_asc', 'price_desc', 'ending_soon'],
            ],
        ]);
    }

    //list auctions created by the authenticated user
    public function managedAuctions(Request $request){
        $perPage = $request->input('per_page', 10);

        $query = Auction::where('user_id', auth()->id())
            ->with(['category', 'images'])
            ->withCount('bids');

        // Status Filter
        if ($request->filled('status')) {
            $status = $request->status;
            if ($status === 'live') {
                $query->active()
                      ->where('start_time', '<=', now())
                      ->where('end_time', '>', now());
            } elseif ($status === 'ended') {
                $query->where('end_time', '<=', now());
            } elseif ($status !== 'all') {
                $query->where('status', $status);
            }
        }

        // Category Filter
        if ($request->filled('category')) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $query->whereIn('category_id', $category->getAllChildIds());
            }
        }

        // Date Range Filter
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Keyword Search
        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        // Sorting
        $sort = $request->input('sort', 'latest');
        match($sort) {
            'price_asc' => $query->orderBy('current_price', 'asc'),
            'price_desc' => $query->orderBy('current_price', 'desc'),
            'ending_soon' => $query->orderBy('end_time', 'asc'),
            default => $query->orderBy('created_at', 'desc'),
        };

        $auctions = $query->paginate($perPage);

        return AuctionResource::collection($auctions)->additional([
            'success' => true,
            'filters_applied' => $request->only(['status', 'category', 'start_date', 'end_date', 'sort', 'q']),
        ]);
    }

    /**
     * Update an auction (User endpoint)
     */
    public function update(UpdateAuctionRequest $request, $id)
    {
        $auction = Auction::findOrFail($id);

        try {
            $updatedAuction = $this->auctionService->updateAuction($auction, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Auction updated successfully',
                'data' => new AuctionResource($updatedAuction)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
}
