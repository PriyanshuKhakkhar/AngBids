<?php

namespace App\Services;

use App\Models\Auction;
use App\Models\Bid;
use Illuminate\Support\Facades\DB;
use Exception;

class BidService
{
    public function placeBid(Auction $auction, array $bidData, $user): array
    {
        // 1. Check if auction is active
        if ($auction->status !== 'active') {
            throw new Exception('Bidding is only allowed on active auctions.');
        }

        // 2. Check if auction has expired
        if ($auction->end_time->isPast()) {
            throw new Exception('This auction has already ended.');
        }

        // 3. User cannot bid on their own auction
        if ($auction->user_id === $user->id) {
            throw new Exception('You cannot bid on your own auction.');
        }

        return DB::transaction(function () use ($auction, $bidData, $user) {
            $isExtended = false;
            $minIncrement = $auction->min_increment ?? 0.01;

            // Handle Proxy Bidding Setup
            if (isset($bidData['max_bid_amount']) && $bidData['max_bid_amount'] > 0) {
                $maxBidAmount = $bidData['max_bid_amount'];
                
                // Ensure max_bid_amount is higher than current price + increment
                $minimumRequired = $auction->current_price + $minIncrement;
                if ($maxBidAmount < $minimumRequired) {
                    throw new Exception('Your maximum bid must be at least ₹' . number_format($minimumRequired, 2));
                }

                \App\Models\AutoBids::updateOrCreate(
                    ['user_id' => $user->id, 'auction_id' => $auction->id],
                    ['max_bid_amount' => $maxBidAmount, 'active' => true]
                );
            }

            // Calculate the manual bid amount if provided
            $manualIncrement = $bidData['increment'] ?? 0;
            $manualBidAmount = $manualIncrement > 0 ? $auction->current_price + $manualIncrement : 0;

            if ($manualIncrement > 0) {
                if ($manualIncrement < $minIncrement) {
                    throw new Exception('The minimum increment required is ₹' . number_format($minIncrement, 2));
                }
                if ($manualIncrement > Auction::MAX_INCREMENT_ALLOWED) {
                    throw new Exception('The bid increment cannot exceed ₹' . number_format(Auction::MAX_INCREMENT_ALLOWED, 2));
                }
            }

            // Determine the "True Winner" and the "Final Price"
            // 1. Get the top competing AutoBid (not the current user)
            $topCompetingAutoBid = $auction->autoBids()
                ->where('active', true)
                ->where('user_id', '!=', $user->id)
                ->orderByDesc('max_bid_amount')
                ->first();

            // 2. Determine the user's best offer (manual bid amount OR their own auto-bid limit)
            $userMax = max($manualBidAmount, (isset($maxBidAmount) ? $maxBidAmount : 0));

            $winnerId = null;
            $newTopPrice = $auction->current_price;

            if ($topCompetingAutoBid) {
                if ($topCompetingAutoBid->max_bid_amount > $userMax) {
                    // Competition stays winner
                    $winnerId = $topCompetingAutoBid->user_id;
                    $newTopPrice = min($topCompetingAutoBid->max_bid_amount, $userMax + $minIncrement);
                } elseif ($topCompetingAutoBid->max_bid_amount == $userMax) {
                    // Tie - Tiebreaker: Earliest proxy wins. 
                    // If competition was already there, they win.
                    $winnerId = $topCompetingAutoBid->user_id;
                    $newTopPrice = $userMax;
                } else {
                    // User takes the lead
                    $winnerId = $user->id;
                    $newTopPrice = min($userMax, $topCompetingAutoBid->max_bid_amount + $minIncrement);
                }
            } else {
                // No competition from other auto-bids
                $winnerId = $user->id;
                
                // Check current leading bidder (who might not have an auto-bid)
                $currentTopBid = $auction->bids()->latest('amount')->first();
                
                if ($currentTopBid && $currentTopBid->user_id !== $user->id) {
                    // We must outbid the current static bid
                    $newTopPrice = min($userMax, $currentTopBid->amount + $minIncrement);
                } else {
                    // We are the current winner or no bids yet
                    // If we just placed a manual bid, use it
                    if ($manualIncrement > 0) {
                        $newTopPrice = $manualBidAmount;
                    } else {
                        // Just setting/updating a proxy, price doesn't necessarily move
                        $newTopPrice = $auction->current_price;
                    }
                }
            }

            // Final safety: price must be at least current_price + minIncrement if bids exist
            // or at least starting_price if no bids exist.
            if ($auction->bids()->exists()) {
                $newTopPrice = max($newTopPrice, $auction->current_price + $minIncrement);
            } else {
                $newTopPrice = max($newTopPrice, $auction->starting_price);
            }
            
            // Ensure we never exceed the winner's actual potential maximum
            if ($winnerId === $user->id) {
                $newTopPrice = min($newTopPrice, $userMax);
            }

            // Anti-Sniping
            $now = now();
            if ($now->greaterThanOrEqualTo($auction->end_time->copy()->subMinutes(2))) {
                $auction->end_time = $auction->end_time->addMinutes(5);
                $isExtended = true;
            }

            // Create the bid
            $bid = Bid::create([
                'auction_id' => $auction->id,
                'user_id' => $winnerId,
                'amount' => $newTopPrice, 
            ]);

            // Update auction
            $auction->update([
                'current_price' => $newTopPrice,
                'end_time' => $auction->end_time
            ]);

            if ($isExtended && $auction->user) {
                $auction->user->notify(new \App\Notifications\AuctionExtendedNotification($auction));
            }

            return [
                'bid' => $bid,
                'is_extended' => $isExtended
            ];
        });
    }
}
