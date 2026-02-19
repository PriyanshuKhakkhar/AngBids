<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\PlaceBidRequest;
use App\Models\Auction;
use App\Services\BidService;
use Illuminate\Http\Request;
use Exception;

class BidController extends Controller
{
    protected $bidService;

    public function __construct(BidService $bidService)
    {
        $this->bidService = $bidService;
    }

    /**
     * Store a newly created bid in storage.
     */
    public function store(PlaceBidRequest $request, Auction $auction)
    {
        try {
            $result = $this->bidService->placeBid($auction, $request->validated(), auth()->user());

            $message = 'Your bid has been placed successfully!';
            if ($result['is_extended']) {
                $message .= ' This auction has been extended by 5 minutes due to fair play rules.';
            }

            return redirect()->back()->with('success', $message);
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
