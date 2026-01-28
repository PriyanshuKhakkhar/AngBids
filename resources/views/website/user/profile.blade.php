@extends('website.layouts.dashboard')

@section('title', 'Profile Settings | LaraBids')

@section('content')

<h2 class="h3 text-dark fw-bold mb-4">Profile Settings</h2>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card card-elite p-4 shadow-sm border-light">
            <h5 class="text-primary fw-bold mb-4">Personal Information</h5>
            <form>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label text-dark fw-bold small">Full Name</label>
                        <input type="text" class="form-control form-control-elite" id="name" 
                            value="{{ auth()->user()->name }}">
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label text-dark fw-bold small">Email Address</label>
                        <input type="email" class="form-control form-control-elite" id="email" 
                            value="{{ auth()->user()->email }}">
                    </div>
                    <div class="col-md-6">
                        <label for="phone" class="form-label text-dark fw-bold small">Phone Number</label>
                        <input type="tel" class="form-control form-control-elite" id="phone" 
                            placeholder="+1 (555) 123-4567">
                    </div>
                    <div class="col-md-6">
                        <label for="location" class="form-label text-dark fw-bold small">Location</label>
                        <input type="text" class="form-control form-control-elite" id="location" 
                            placeholder="City, Country">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary px-4">Save Changes</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="card card-elite p-4 mt-4 shadow-sm border-light">
            <h5 class="text-primary fw-bold mb-4">Change Password</h5>
            <form>
                <div class="row g-3">
                    <div class="col-12">
                        <label for="current-password" class="form-label text-dark fw-bold small">Current Password</label>
                        <input type="password" class="form-control form-control-elite" id="current-password">
                    </div>
                    <div class="col-md-6">
                        <label for="new-password" class="form-label text-dark fw-bold small">New Password</label>
                        <input type="password" class="form-control form-control-elite" id="new-password">
                    </div>
                    <div class="col-md-6">
                        <label for="confirm-password" class="form-label text-dark fw-bold small">Confirm Password</label>
                        <input type="password" class="form-control form-control-elite" id="confirm-password">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary px-4">Update Password</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-elite p-4 text-center shadow-sm border-light">
            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=4e73df&color=ffffff&size=150"
                class="rounded-circle border border-light shadow-sm mb-3 mx-auto" width="120" alt="Avatar">
            <h5 class="text-dark fw-bold mb-1">{{ auth()->user()->name }}</h5>
            <p class="text-secondary small mb-3">{{ auth()->user()->email }}</p>
            <button class="btn btn-outline-primary btn-sm rounded-pill px-3">Change Avatar</button>
        </div>

        <div class="card card-elite p-4 mt-4 shadow-sm border-light">
            <h6 class="text-primary fw-bold mb-3">Account Statistics</h6>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-dark small">Member Since</span>
                <span class="text-primary small fw-bold">{{ auth()->user()->created_at->format('M Y') }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-dark small">Total Bids</span>
                <span class="text-primary small fw-bold">0</span>
            </div>
            <div class="d-flex justify-content-between">
                <span class="text-dark small">Items Won</span>
                <span class="text-success small fw-bold">0</span>
            </div>
        </div>
    </div>
</div>


@endsection
