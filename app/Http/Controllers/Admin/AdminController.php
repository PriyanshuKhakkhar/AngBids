<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function auctions()
    {
        return view('admin.auctions.index');
    }

    public function bids()
    {
        return view('admin.bids.index');
    }

    public function users()
    {
        return view('admin.users.index');
    }

    public function payments()
    {
        return view('admin.payments.index');
    }

    public function reports()
    {
        return view('admin.reports');
    }

    public function categories()
    {
        return view('admin.categories.index');
    }

    public function settings()
    {
        return view('admin.settings');
    }

    public function notifications()
    {
        return view('admin.notifications');
    }

    public function profile()
    {
        return view('admin.profile');
    }



    public function blank()
    {
        return view('admin.blank');
    }
}
