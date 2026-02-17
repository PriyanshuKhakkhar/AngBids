<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use App\Models\Auction;
use App\Models\Contact;
use App\Http\Resources\AuctionResource;
use App\Http\Requests\ContactRequest;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    /**
     * Get home page data
     * Returns latest 3 active auctions with user, category, and images
     */
    public function index()
    {
        $auctions = Auction::active()
            ->latestFirst()
            ->take(3)
            ->with(['user', 'category', 'images'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'auctions' => AuctionResource::collection($auctions)
            ]
        ]);
    }

    /**
     * Store contact form submission
     */
    public function contactStore(ContactRequest $request)
    {
        $contact = Contact::create([
            'name' => $request->name,
            'email' => auth()->check() ? auth()->user()->email : $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'unread'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thank you for contacting us! We will get back to you soon.',
            'data' => $contact
        ], 201);
    }
}
