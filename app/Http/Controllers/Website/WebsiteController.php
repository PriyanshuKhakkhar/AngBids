<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Auction;
use App\Models\Category;
use App\Http\Requests\ContactRequest;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    // Home page
    public function index()
    {
        // Live Auctions
        $auctions = Auction::live()
            ->latestFirst()
            ->take(8)
            ->with(['user', 'category', 'watchlists' => function($q) {
                if (auth()->check()) {
                    $q->where('user_id', auth()->id());
                } else {
                    $q->whereRaw('1 = 0');
                }
            }])
            ->get();
            
        // Upcoming Auctions (Starts in the future)
        $upcomingAuctions = Auction::where('status', 'active')
            ->where('start_time', '>', now())
            ->orderBy('start_time', 'asc')
            ->take(4)
            ->with(['user', 'category', 'watchlists' => function($q) {
                if (auth()->check()) {
                    $q->where('user_id', auth()->id());
                } else {
                    $q->whereRaw('1 = 0');
                }
            }])
            ->get();
            
        $testimonials = \App\Models\Testimonial::where('is_active', true)->take(3)->get();

        return view('website.index', compact('auctions', 'upcomingAuctions', 'testimonials'));
    }

    // Dashboard redirect
    public function dashboard()
    {
        if (auth()->user()->role === 'admin' || auth()->user()->role === 'super admin') {
            return redirect()->route('admin.dashboard');
        }
        
        return redirect()->route('user.dashboard');
    }

    // About page
    public function about()
    {
        return view('website.about');
    }

    // Contact page
    public function contact()
    {
        return view('website.contact');
    }

    /**
     * Store contact form submission
     */
    public function contactStore(ContactRequest $request)
    {
        // Check if user is logged in
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Please login first to send us a message.')
                ->withInput();
        }

        \App\Models\Contact::create([
            'name' => $request->name,
            'email' => auth()->user()->email, // Use logged-in user's email
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'unread'
        ]);

        return back()->with('success', 'Thank you for contacting us! We will get back to you soon.');
    }
}
