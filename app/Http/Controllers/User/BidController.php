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
            $user = auth()->user();
            $result = $this->bidService->placeBid($auction, $request->validated(), $user);

            // Determine if the user who placed the bid is actually the current winner
            // If they are not, it means an auto-bid outbid them instantly.
            $currentWinnerId = (int)$result['bid']->user_id;
            
            if ($currentWinnerId === (int)$user->id) {
                $message = 'Great news! Your bid has been placed successfully and you are currently the highest bidder.';
                if ($result['is_extended']) {
                    $message .= ' This auction has been extended by 5 minutes to give everyone a fair chance to respond.';
                }
                return redirect()->back()->with('success', $message);
            } else {
                $minNextBid = number_format($auction->current_price + ($auction->min_increment ?? 0.01), 2);
                $message = 'You were instantly outbid! Another user has a higher automatic "Proxy Bid" limit. To take the lead, you will need to bid at least ₹' . $minNextBid . '.';
                return redirect()->back()->with('warning', $message);
            }

        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
