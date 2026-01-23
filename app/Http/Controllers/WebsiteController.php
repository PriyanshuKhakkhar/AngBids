<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    /**
     * Display the home page
     */
    public function index()
    {
        return view('website.index');
    }

    /**
     * Display the about page
     */
    public function about()
    {
        return view('website.about');
    }

    /**
     * Display the contact page
     */
    public function contact()
    {
        return view('website.contact');
    }

    /**
     * Handle contact form submission
     */
    public function contactStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // TODO: Implement contact form logic (send email, save to database, etc.)
        
        return back()->with('success', 'Thank you for contacting us! We will get back to you soon.');
    }
}
