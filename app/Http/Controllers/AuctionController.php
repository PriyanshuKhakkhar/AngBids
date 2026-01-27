<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Category;
use Illuminate\Http\Request;

class AuctionController extends Controller
{
    /**
     * Display a listing of auctions
     */
    public function index(Request $request)
    {
        $query = Auction::query()->where('status', 'active');

        // Search filter
        if ($request->has('q')) {
            $search = $request->input('q');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('category', function($catQ) use ($search) {
                      $catQ->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Category filter
        if ($request->has('category')) {
            $categorySlug = $request->input('category');
            $query->whereHas('category', function($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        // Paginate results
        $auctions = $query->latest()
            ->with(['user', 'category'])
            ->paginate(9)
            ->withQueryString();

        // Get active categories for the filter bar
        $categories = Category::where('is_active', true)->get();

        return view('website.auctions.index', compact('auctions', 'categories'));
    }

    /**
     * Display the specified auction
     */
    public function show($id)
    {
        $auction = Auction::with(['user', 'category'])->findOrFail($id);
        return view('website.auctions.show', compact('auction'));
    }

    /**
     * Search auctions
     */
    public function search(Request $request)
    {
        return $this->index($request);
    }
}
