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
}
