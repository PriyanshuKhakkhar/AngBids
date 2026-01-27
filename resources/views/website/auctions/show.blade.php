@extends('website.layouts.app')

@section('title', 'Auction Details | LaraBids')

@section('content')

<!-- Breadcrumb -->
<section class="breadcrumb-elite text-center text-white py-5">
    <div class="container">
        <h1 class="display-4 fw-bold mb-3">Auction <span class="gold-text">Details</span></h1>
        <p class="lead opacity-75">Item #{{ $auction->id }}</p>
    </div>
</section>

<!-- Auction Details -->
<section class="py-5">
    <div class="container py-lg-5">
        <div class="row g-5">
            <!-- Image Gallery -->
            <div class="col-lg-6" data-aos="fade-right">
                <div class="position-relative overflow-hidden rounded-3 mb-3" style="height: 500px;">
                    @if($auction->image)
                        <img src="{{ asset('storage/' . $auction->image) }}" class="w-100 h-100 object-fit-cover" alt="{{ $auction->title }}">
                    @else
                        <img src="https://images.unsplash.com/photo-1523275335684-21481017106d?auto=format&fit=crop&w=1200"
                            class="w-100 h-100 object-fit-cover" alt="{{ $auction->title }}">
                    @endif
                </div>
            </div>

            <!-- Auction Info -->
            <div class="col-lg-6" data-aos="fade-left">
                <div class="glass-panel p-4 mb-4">
                    <h2 class="h3 text-white mb-3">{{ $auction->title }}</h2>
                    <p class="text-secondary mb-4">{{ $auction->category->name ?? 'Uncategorized' }}</p>

                    <!-- Timer -->
                    @php
                        $now = \Carbon\Carbon::now();
                        $end = \Carbon\Carbon::parse($auction->end_time);
                        $diff = $now->diff($end);
                        $isClosed = $now->greaterThan($end);
                    @endphp

                    @if(!$isClosed)
                    <div class="glass-timer text-center py-3 timer-val mb-4" 
                        data-days="{{ $diff->d }}" data-hours="{{ $diff->h }}" data-min="{{ $diff->i }}" data-sec="{{ $diff->s }}">
                        <div class="row g-0 px-3">
                            <div class="col">
                                <div class="fw-bold fs-4" data-days>{{ sprintf('%02d', $diff->d) }}</div>
                                <small class="opacity-50">DAYS</small>
                            </div>
                            <div class="col">
                                <div class="fw-bold fs-4" data-hours>{{ sprintf('%02d', $diff->h) }}</div>
                                <small class="opacity-50">HRS</small>
                            </div>
                            <div class="col">
                                <div class="fw-bold fs-4" data-min>{{ sprintf('%02d', $diff->i) }}</div>
                                <small class="opacity-50">MIN</small>
                            </div>
                            <div class="col">
                                <div class="fw-bold fs-4" data-sec>{{ sprintf('%02d', $diff->s) }}</div>
                                <small class="opacity-50">SEC</small>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="alert alert-danger py-3 mb-4 text-center fw-bold">This auction has closed.</div>
                    @endif

                    <!-- Current Bid -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-secondary">Current Bid</span>
                            <span class="h3 mb-0 gold-text fw-bold">${{ number_format($auction->current_price, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-secondary small">Starting Bid</span>
                            <span class="text-white small">${{ number_format($auction->starting_price, 2) }}</span>
                        </div>
                    </div>

                    <!-- Bid Form -->
                    @if(!$isClosed)
                        @auth
                            <form action="#" method="POST" class="mb-4">
                                @csrf
                                <label for="bid-amount" class="form-label text-white">Your Bid Amount</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-dark text-gold border-gold">$</span>
                                    <input type="number" class="form-control form-control-elite" id="bid-amount" 
                                        placeholder="{{ number_format($auction->current_price + 10, 2, '.', '') }}" 
                                        min="{{ $auction->current_price + 0.01 }}" step="0.01">
                                </div>
                                <button type="submit" class="btn btn-gold w-100 py-3">Place Bid</button>
                            </form>
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-info-circle me-2"></i>
                                Please <a href="{{ route('login') }}" class="text-decoration-underline">login</a> to place a bid.
                            </div>
                        @endauth
                    @endif

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-gold flex-fill">
                            <i class="fas fa-heart me-2"></i>Add to Wishlist
                        </button>
                        <button class="btn btn-outline-gold">
                            <i class="fas fa-share-alt"></i>
                        </button>
                    </div>
                </div>

                <!-- Item Details -->
                <div class="glass-panel p-4">
                    <h4 class="h5 text-white mb-3">Item Details</h4>
                    <ul class="list-unstyled text-secondary small">
                        <li class="mb-2"><strong class="text-white">Category:</strong> {{ $auction->category->name ?? 'Uncategorized' }}</li>
                        <li class="mb-2"><strong class="text-white">Seller:</strong> {{ $auction->user->name }}</li>
                        <li class="mb-2"><strong class="text-white">Status:</strong> <span class="badge bg-gold text-dark">{{ ucfirst($auction->status) }}</span></li>
                        <li class="mb-2"><strong class="text-white">Starts:</strong> {{ $auction->start_time->format('M d, Y H:i') }}</li>
                        <li class="mb-2"><strong class="text-white">Ends:</strong> {{ $auction->end_time->format('M d, Y H:i') }}</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Description & Bid History -->
        <div class="row g-5 mt-4">
            <div class="col-lg-8">
                <div class="glass-panel p-4" data-aos="fade-up">
                    <h4 class="h5 text-white mb-3">Description</h4>
                    <div class="text-secondary">
                        {!! nl2br(e($auction->description)) !!}
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="glass-panel p-4" data-aos="fade-up" data-aos-delay="100">
                    <h4 class="h5 text-white mb-3">Recent Bids</h4>
                    <ul class="list-unstyled">
                        <li class="text-secondary small py-3 text-center">No bids placed yet.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
