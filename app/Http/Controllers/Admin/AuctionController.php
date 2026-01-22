<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Auction;
use Illuminate\Http\Request;

class AuctionController extends Controller
{
    public function index()
    {
        $auctions = Auction::with('user')->latest()->paginate(10);
        return view('admin.auctions.index', compact('auctions'));
    }

    public function show(Auction $auction)
    {
        return view('admin.auctions.show', compact('auction'));
    }

    public function destroy(Auction $auction)
    {
        $auction->delete();
        return redirect()->route('admin.auctions.index')->with('success', 'Auction deleted successfully.');
    }

    public function cancel(Auction $auction)
    {
        $auction->update(['status' => 'cancelled']);
        return redirect()->back()->with('success', 'Auction cancelled successfully.');
    }
}
