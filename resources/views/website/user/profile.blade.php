@extends('website.layouts.dashboard')

@section('title', 'Profile Settings | LaraBids')

@section('content')

<h2 class="h3 text-white fw-bold mb-4">Profile Settings</h2>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="glass-panel p-4">
            <h5 class="text-white mb-4">Personal Information</h5>
            <form>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label text-white">Full Name</label>
                        <input type="text" class="form-control form-control-elite" id="name" 
                            value="{{ auth()->user()->name }}">
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label text-white">Email Address</label>
                        <input type="email" class="form-control form-control-elite" id="email" 
                            value="{{ auth()->user()->email }}">
                    </div>
                    <div class="col-md-6">
                        <label for="phone" class="form-label text-white">Phone Number</label>
                        <input type="tel" class="form-control form-control-elite" id="phone" 
                            placeholder="+1 (555) 123-4567">
                    </div>
                    <div class="col-md-6">
                        <label for="location" class="form-label text-white">Location</label>
                        <input type="text" class="form-control form-control-elite" id="location" 
                            placeholder="City, Country">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-gold px-4">Save Changes</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="glass-panel p-4 mt-4">
            <h5 class="text-white mb-4">Change Password</h5>
            <form>
                <div class="row g-3">
                    <div class="col-12">
                        <label for="current-password" class="form-label text-white">Current Password</label>
                        <input type="password" class="form-control form-control-elite" id="current-password">
                    </div>
                    <div class="col-md-6">
                        <label for="new-password" class="form-label text-white">New Password</label>
                        <input type="password" class="form-control form-control-elite" id="new-password">
                    </div>
                    <div class="col-md-6">
                        <label for="confirm-password" class="form-label text-white">Confirm Password</label>
                        <input type="password" class="form-control form-control-elite" id="confirm-password">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-gold px-4">Update Password</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="glass-panel p-4 text-center">
            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=d4af37&color=0a192f&size=150"
                class="rounded-circle border border-gold border-opacity-25 mb-3" width="150" alt="Avatar">
            <h5 class="text-white mb-1">{{ auth()->user()->name }}</h5>
            <p class="text-secondary small mb-3">{{ auth()->user()->email }}</p>
            <button class="btn btn-outline-gold btn-sm">Change Avatar</button>
        </div>

        <div class="glass-panel p-4 mt-4">
            <h6 class="text-white mb-3">Account Statistics</h6>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-secondary small">Member Since</span>
                <span class="text-white small">{{ auth()->user()->created_at->format('M Y') }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-secondary small">Total Bids</span>
                <span class="text-white small">0</span>
            </div>
            <div class="d-flex justify-content-between">
                <span class="text-secondary small">Items Won</span>
                <span class="text-gold small fw-bold">0</span>
            </div>
        </div>
    </div>
</div>

@endsection
