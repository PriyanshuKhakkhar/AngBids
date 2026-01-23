<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    /**
     * Display user dashboard
     */
    public function index()
    {
        return view('website.user.dashboard');
    }

    /**
     * Display user's bids
     */
    public function myBids()
    {
        // TODO: Fetch user's bids from database
        return view('website.user.my-bids');
    }

    /**
     * Display user's winning items
     */
    public function winningItems()
    {
        // TODO: Fetch user's winning items from database
        return view('website.user.winning-items');
    }

    /**
     * Display user's wishlist
     */
    public function wishlist()
    {
        // TODO: Fetch user's wishlist from database
        return view('website.user.wishlist');
    }

    /**
     * Display user's profile
     */
    public function profile()
    {
        return view('website.user.profile');
    }
}
