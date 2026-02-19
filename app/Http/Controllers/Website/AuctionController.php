<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Auction;
use App\Models\Category;
use App\Http\Requests\StoreAuctionRequest;
use App\Services\AuctionService;
use Illuminate\Http\Request;

class AuctionController extends Controller
{
    protected $auctionService;

    public function __construct(AuctionService $auctionService)
    {
        $this->auctionService = $auctionService;
    }

    // List auctions
    public function index(Request $request)
    {
        $auctions = $this->auctionService->getFilteredAuctions($request)
            ->paginate(12)
            ->withQueryString();

        $categories = Category::topLevel()->active()->with('children')->get();
        $currentCategory = null;

        if ($request->filled('category')) {
            $currentCategory = Category::where('slug', $request->category)->first();
        }

        return view('website.auctions.index', compact('auctions', 'categories', 'currentCategory'));
    }

    // Show auction
    public function show($id)
    {
        $auction = Auction::with(['user', 'category', 'images', 'bids.user', 'watchlists' => function($q) {
            if (auth()->check()) {
                $q->where('user_id', auth()->id());
            } else {
                $q->whereRaw('1 = 0');
            }
        }])->findOrFail($id);

        // if auction is not active, only owner or admin can view
        if ($auction->status !== 'active') {
            if (!auth()->check()) {
                abort(404);
            }

            // Check if user is owner or admin
            // Assuming 'role' column exists on User model for admin check
            $user = auth()->user();
            if ($user->id !== $auction->user_id && $user->role !== 'admin' && $user->role !== 'super admin') {
                 abort(404);
            }
        }

        return view('website.auctions.show', compact('auction'));
    }

    // Create form
    public function create()
    {
        $categories = Category::topLevel()->active()->with('children')->get();
        
        $categoryTree = $categories->map(function($cat) {
            return [
                'id' => $cat->id,
                'name' => $cat->name,
                'slug' => $cat->slug,
                'children' => $cat->children->map(function($child) {
                    return ['id' => $child->id, 'name' => $child->name, 'slug' => $child->slug];
                })
            ];
        });

        return view('website.auctions.create', compact('categories', 'categoryTree'));
    }

    // Store auction
    public function store(StoreAuctionRequest $request)
    {
        $auction = $this->auctionService->createAuction($request->validated(), auth()->user());

        $message = $auction->status === 'active' 
            ? 'Auction created successfully and is now live!' 
            : 'Auction created successfully! It will be live after admin approval.';

        return redirect()->route('auctions.show', $auction->id)
            ->with('success', $message);
    }

    // Search auctions
    public function search(Request $request)
    {
        return $this->index($request);
    }
}

