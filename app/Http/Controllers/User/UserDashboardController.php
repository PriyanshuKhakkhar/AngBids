<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Contact;
use Yajra\DataTables\Facades\DataTables;

class UserDashboardController extends Controller
{
    // Dashboard
    public function index()
    {
        $user = auth()->user();

        $stats = [
            'active_bids'      => $user->bids()->count(),
            'total_wins'       => $user->getWonAuctionsCount(),
            'watchlist_count'  => $user->watchlist()->count(),
            'messages_count'   => 0,
        ];

        return view('website.user.dashboard', compact('stats'));
    }

    // My bids
    public function myBids()
    {
        $bids = auth()->user()
            ->bids() 
            ->with('auction')
            ->latest()
            ->get()
            ->unique('auction_id');

        return view('website.user.my-bids', compact('bids'));
    }

    // My auctions
    public function myAuctions()
    {
        $auctions = auth()->user()
            ->auctions()
            ->with(['category', 'bids.user'])
            ->latest()
            ->paginate(10);

        return view('website.user.my-auctions', compact('auctions'));
    }

    // Winning items
    public function winningItems()
    {
        $user = auth()->user();
        
        $auctions = \App\Models\Auction::where('end_time', '<=', now())
            ->whereIn('status', ['active', 'closed'])
            ->whereHas('bids', function ($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->whereRaw('amount = (SELECT MAX(amount) FROM bids WHERE auction_id = auctions.id)');
            })
            ->with(['category', 'user'])
            ->latest()
            ->paginate(10);

        return view('website.user.winning-items', compact('auctions'));
    }

    // Watchlist
    public function watchlist()
    {
        $watchlists = auth()->user()
            ->watchlist()
            ->with(['auction.category', 'auction.bids'])
            ->latest()
            ->paginate(10);

        return view('website.user.watchlist', compact('watchlists'));
    }

    // Show single message (contact)
    public function showMessage($id)
    {
        $contact = Contact::withTrashed()
            ->where('email', auth()->user()->email)
            ->findOrFail($id);

        return view('website.user.message_show', compact('contact'));
    }

    // Profile
    public function profile()
    {
        return view('website.user.profile');
    }
}
