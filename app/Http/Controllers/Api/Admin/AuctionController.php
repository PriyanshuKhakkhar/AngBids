<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Auction;
use App\Models\Category;
use App\Services\AuctionService;
use App\Http\Resources\AuctionResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;

class AuctionController extends Controller
{
    protected $auctionService;

    public function __construct(AuctionService $auctionService)
    {
        $this->auctionService = $auctionService;
    }

    // All Auctions
    public function index(Request $request)
    {
        // Use the filter logic from AuctionService but adapt for API response format
        // The service returns a query builder, perfect.
        $query = $this->auctionService
            ->getFilteredAuctions($request, false) // false = all status
            ->select('auctions.*') // ensure select
            ->withTrashed()
            ->with(['user', 'category', 'images', 'bids.user']);

        $auctions = $query->paginate(10); // Standard pagination

        return AuctionResource::collection($auctions)->additional([
            'status' => true,
            'message' => 'Auctions retrieved successfully'
        ]);
    }

    // Create Auction
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'       => 'required|exists:users,id',
            'category_id'   => 'required|exists:categories,id',
            'title'         => 'required|string|max:255',
            'description'   => 'required|string',
            'starting_price'=> 'required|numeric|min:0',
            'start_time'    => 'required|date',
            'end_time'      => 'required|date|after:start_time',
            'status'        => 'nullable|in:active,pending,closed,cancelled',
            // Add image validation if needed but usually handled inside service or separately
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Prepare data array for service
        $data = $request->all();
        
        // Handle file uploads if any (though API usually sends as multipart/form-data)
        // Handle file uploads
        // The service expects 'images' to be an array of UploadedFile objects
        if ($request->hasFile('images')) {
             $data['images'] = $request->file('images');
             // Ensure it is an array if single file uploaded
             if (!is_array($data['images'])) {
                 $data['images'] = [$data['images']];
             }
        }

        // We need a user object to pass to createAuction
        $user = \App\Models\User::find($request->user_id);

        $auction = $this->auctionService->createAuction($data, $user);

        return response()->json([
            'status'  => true,
            'message' => 'Auction Created Successfully',
            'data'    => new AuctionResource($auction)
        ], 201);
    }

    // Show Single Auction
    public function show($id)
    {
        $auction = Auction::withTrashed()
            ->with(['user', 'category', 'images', 'bids.user'])
            ->find($id);

        if (!$auction) {
            return response()->json([
                'status'  => false,
                'message' => 'Auction not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data'   => new AuctionResource($auction)
        ]);
    }

    // Update Auction (General Update)
    public function update(Request $request, $id)
    {
        $auction = Auction::withTrashed()->find($id);

        if (!$auction) {
            return response()->json([
                'status'  => false,
                'message' => 'Auction not found'
            ], 404);
        }

        // Similar validation to store, but 'sometimes'
        $validator = Validator::make($request->all(), [
            'category_id'   => 'sometimes|exists:categories,id',
            'title'         => 'sometimes|string|max:255',
            'description'   => 'sometimes|string',
            'starting_price'=> 'sometimes|numeric|min:0',
            'start_time'    => 'sometimes|date',
            'end_time'      => 'sometimes|date|after:start_time',
            // Status updates should use specific endpoints, but we allow admin to force update fields here
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        $auction->update($request->except(['images'])); // Images handling complex update logic usually separate

        return response()->json([
            'status'  => true,
            'message' => 'Auction Updated Successfully',
            'data'    => new AuctionResource($auction)
        ]);
    }

    // Approve Auction
    public function approve($id)
    {
        $auction = Auction::withTrashed()->find($id);

        if (!$auction) {
            return response()->json([
                'status'  => false,
                'message' => 'Auction not found'
            ], 404);
        }

        $this->auctionService->updateStatus($auction, 'active');

        return response()->json([
            'status'  => true,
            'message' => 'Auction approved successfully',
            'data'    => new AuctionResource($auction->fresh())
        ]);
    }

    // Cancel Auction
    public function cancel(Request $request, $id)
    {
        $auction = Auction::withTrashed()->find($id);

        if (!$auction) {
            return response()->json([
                'status'  => false,
                'message' => 'Auction not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'reason' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        $this->auctionService->updateStatus($auction, 'cancelled', $request->reason);

        return response()->json([
            'status'  => true,
            'message' => 'Auction cancelled successfully',
            'data'    => new AuctionResource($auction->fresh())
        ]);
    }


    // Soft Delete
    public function destroy($id)
    {
        // Use service logic if it does more than delete (it doesn't seem to currently, but good practice)
        // Accessing service directly if it has delete method? Yes: deleteAuction($id)
        
        $auction = Auction::find($id);
        
        if (!$auction) {
             return response()->json([
                'status'  => false,
                'message' => 'Auction not found'
            ], 404);
        }
        
        $this->auctionService->deleteAuction($id);

        return response()->json([
            'status'  => true,
            'message' => 'Auction moved to trash'
        ]);
    }

    // Restore
    public function restore($id)
    {
        $auction = Auction::withTrashed()->find($id);

        if (!$auction) {
            return response()->json([
                'status'  => false,
                'message' => 'Auction not found'
            ], 404);
        }

        $this->auctionService->restoreAuction($id);

        return response()->json([
            'status'  => true,
            'message' => 'Auction restored successfully'
        ]);
    }

    // Force Delete
    public function forceDelete($id)
    {
        $auction = Auction::withTrashed()->find($id);

        if (!$auction) {
            return response()->json([
                'status'  => false,
                'message' => 'Auction not found'
            ], 404);
        }
        
        $this->auctionService->forceDeleteAuction($id);

        return response()->json([
            'status'  => true,
            'message' => 'Auction permanently deleted'
        ]);
    }
}
