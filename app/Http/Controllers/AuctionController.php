<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuctionController extends Controller
{
    /**
     * Display a listing of auctions
     */
    public function index()
    {
        // TODO: Fetch auctions from database
        return view('website.auctions.index');
    }

    /**
     * Display the specified auction
     */
    public function show($id)
    {
        // TODO: Fetch auction details from database
        return view('website.auctions.show', compact('id'));
    }

    /**
     * Search auctions
     */
    public function search(Request $request)
    {
        // TODO: Implement search functionality
        $query = $request->input('q');
        return view('website.auctions.index', compact('query'));
    }
}
