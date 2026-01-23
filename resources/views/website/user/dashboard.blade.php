@extends('website.layouts.dashboard')

@section('title', 'My Dashboard | LaraBids')

@section('content')

<!-- Dashboard Header -->
<div class="d-flex justify-content-between align-items-center mb-5">
    <div>
        <span class="badge-elite mb-1 d-block opacity-75">Welcome Back,</span>
        <h1 class="h3 text-white fw-bold mb-0">{{ auth()->user()->name }}</h1>
    </div>
    <div class="d-flex align-items-center gap-4">
        <div class="glass-panel p-2 px-3 small border-0">
            <i class="fas fa-wallet text-gold me-2"></i> Balance: <span
                class="text-white fw-bold">$0.00</span>
        </div>
        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=d4af37&color=0a192f"
            class="rounded-circle border border-gold border-opacity-25" height="45" alt="Avatar">
    </div>
</div>

<!-- Stats Grid -->
<div class="row g-4 mb-5">
    <div class="col-md-4">
        <div class="glass-panel p-4 border-0">
            <p class="small text-secondary mb-1">Active Bids</p>
            <h4 class="h2 text-white fw-bold mb-0">0</h4>
        </div>
    </div>
    <div class="col-md-4">
        <div class="glass-panel p-4 border-0">
            <p class="small text-secondary mb-1">Total Wins</p>
            <h4 class="h2 text-gold fw-bold mb-0">0</h4>
        </div>
    </div>
    <div class="col-md-4">
        <div class="glass-panel p-4 border-0">
            <p class="small text-secondary mb-1">Watchlist Items</p>
            <h4 class="h2 text-white fw-bold mb-0">0</h4>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="glass-panel p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="display-font text-white h6 mb-0">Active Bids Monitoring</h5>
        <a href="{{ route('user.my-bids') }}" class="small text-gold text-decoration-none">View All</a>
    </div>

    <div class="table-responsive">
        <table class="table table-dark table-hover mb-0" style="--bs-table-bg: transparent;">
            <thead class="text-secondary small">
                <tr>
                    <th class="border-secondary border-opacity-10 py-3">AUCTION ITEM</th>
                    <th class="border-secondary border-opacity-10 py-3">STATUS</th>
                    <th class="border-secondary border-opacity-10 py-3">CURRENT BID</th>
                    <th class="border-secondary border-opacity-10 py-3 text-end">ACTION</th>
                </tr>
            </thead>
            <tbody class="text-white align-middle">
                <tr>
                    <td colspan="4" class="text-center py-5 text-secondary">
                        <i class="fas fa-inbox fs-1 mb-3 d-block opacity-25"></i>
                        No active bids yet. <a href="{{ route('auctions.index') }}" class="text-gold">Browse Auctions</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection
