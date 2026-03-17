<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Auction;
use App\Models\AuctionRegistration;
use App\Http\Resources\AuctionRegistrationResource;
use Illuminate\Http\Request;
use Exception;

class AuctionRegistrationController extends Controller
{
    /**
     * Get the registration status for the current user for a specific auction.
     */
    public function status(Request $request, $id)
    {
        $auction = Auction::findOrFail($id);
        $user = $request->user();

        $registration = AuctionRegistration::where('user_id', $user->id)
            ->where('auction_id', $auction->id)
            ->first();

        return response()->json([
            'success' => true,
            'is_registered' => (bool)$registration,
            'registration' => $registration ? new AuctionRegistrationResource($registration) : null,
            'can_register' => !$registration && $user->isKycApproved() && $auction->user_id !== $user->id
        ]);
    }

    /**
     * Register the current user for an auction.
     */
    public function register(Request $request, $id)
    {
        $auction = Auction::findOrFail($id);
        $user = $request->user();

        try {
            // 1. Check if user is the owner
            if ($auction->user_id === $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot register for your own auction.'
                ], 403);
            }

            // 2. Check KYC status
            if (!$user->isKycApproved()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must complete your Identity Verification (KYC) before registering for auctions.',
                    'kyc_status' => $user->kyc ? $user->kyc->status : 'not_submitted'
                ], 403);
            }

            // 3. Check if already registered
            if ($user->isRegisteredFor($auction)) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are already registered for this auction.'
                ], 422);
            }

            // 4. Create registration
            $registration = AuctionRegistration::create([
                'user_id' => $user->id,
                'auction_id' => $auction->id,
                'status' => 'registered'
            ]);

            $message = 'Successfully registered for ' . $auction->title;
            if ($auction->start_time->isFuture()) {
                $message .= '. We will notify you 30 minutes before bidding opens!';
            }

            return (new AuctionRegistrationResource($registration))->additional([
                'success' => true,
                'message' => $message
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to register for the auction. Please try again later.'
            ], 500);
        }
    }
}
