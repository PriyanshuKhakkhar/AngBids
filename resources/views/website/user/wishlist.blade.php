@extends('website.layouts.dashboard')

@section('title', 'Wishlist | LaraBids')

@section('content')

<h2 class="h3 text-dark fw-bold mb-4">My Watchlist</h2>

<div class="row g-4">
    <div class="col-12">
        <div class="card card-elite p-5 text-center shadow-sm border-light">
            <i class="fas fa-heart fs-1 text-primary mb-3 d-block opacity-25"></i>
            <h5 class="text-dark fw-bold mb-2">Your Watchlist is Empty</h5>
            <p class="text-secondary mb-4">Save items you're interested in to keep track of them easily.</p>
            <div>
                <a href="{{ route('auctions.index') }}" class="btn btn-primary px-4">Browse Auctions</a>
            </div>
        </div>
    </div>
</div>


@endsection
