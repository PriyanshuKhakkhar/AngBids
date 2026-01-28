@extends('website.layouts.app')

@section('title', 'Auction Details | LaraBids')

@section('content')

<!-- Hero Header -->
<section class="hero-section text-white d-flex align-items-center">
    <div class="container" data-aos="fade-up">
        <div class="row align-items-center">
            <div class="col-lg-7 text-center text-lg-start mb-5 mb-lg-0">
                <span class="badge bg-white text-primary fw-bold px-3 py-2 rounded-pill mb-3 shadow-sm">
                    🔍 AUCTION PREVIEW
                </span>
                <h1 class="display-3 fw-bold mb-3">Auction <span class="text-white">Details</span></h1>
                <p class="lead opacity-75 pe-lg-5">
                    Viewing item #{{ $auction->id }}. Review details, check bid history, and place your bid for a chance to win.
                </p>
            </div>
            <div class="col-lg-5 d-none d-lg-block">
                <div class="hero-illustration-wrapper ps-lg-4 text-center">
                    <div class="hero-glow-blob"></div>
                    <img src="{{ asset('assets/images/banner-3.png') }}" 
                         class="img-fluid" 
                         alt="Auction Details LaraBids"
                         style="max-height: 350px; filter: drop-shadow(0 20px 50px rgba(0,0,0,0.3));">
                </div>
            </div>
        </div>
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
                        <img src="{{ str_starts_with($auction->image, 'http') ? $auction->image : asset('storage/' . $auction->image) }}" class="w-100 h-100 object-fit-cover main-auction-image" alt="{{ $auction->title }}">
                    @else
                        <img src="https://images.unsplash.com/photo-1523275335684-21481017106d?auto=format&fit=crop&w=1200"
                            class="w-100 h-100 object-fit-cover main-auction-image" alt="{{ $auction->title }}">
                    @endif
                </div>
                
                <!-- Thumbnail Slider (Static for now) -->
                <div class="row g-2">
                    <div class="col-3">
                        <div class="rounded-3 overflow-hidden border border-primary border-2 shadow-sm" style="height: 80px; cursor: pointer;">
                            <img src="{{ str_starts_with($auction->image, 'http') ? $auction->image : asset('storage/' . $auction->image) }}" class="w-100 h-100 object-fit-cover opacity-100" alt="Thumb 1">
                        </div>
                    </div>
                    @for($i = 1; $i <= 3; $i++)
                    <div class="col-3">
                        <div class="rounded-3 overflow-hidden border border-light shadow-sm thumb-inactive" style="height: 80px; cursor: pointer; transition: all 0.2s;">
                            <img src="https://picsum.photos/400/300?random={{ $i + 10 }}" class="w-100 h-100 object-fit-cover" alt="Thumb {{ $i + 1 }}">
                        </div>
                    </div>
                    @endfor
                </div>

            </div>

            <!-- Auction Info -->
            <div class="col-lg-6" data-aos="fade-left">
                <div class="card card-elite p-4 mb-4 border-0 shadow-sm">
                    <h2 class="h3 text-dark mb-3 fw-bold">{{ $auction->title }}</h2>
                    <p class="badge bg-light text-primary mb-4">{{ $auction->category->name ?? 'Uncategorized' }}</p>


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
                    <div class="mb-4 p-3 bg-light rounded-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-secondary small fw-bold">Current Bid</span>
                            <span class="h3 mb-0 text-primary fw-bold">${{ number_format($auction->current_price, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-secondary small">Starting Bid</span>
                            <span class="text-dark small fw-bold">${{ number_format($auction->starting_price, 2) }}</span>
                        </div>
                    </div>


                    <!-- Bid Form -->
                    @if(!$isClosed)
                        @auth
                            <form action="#" method="POST" class="mb-4">
                                @csrf
                                <label for="bid-amount" class="form-label text-dark fw-bold">Your Bid Amount</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-light text-primary border-light fw-bold">$</span>
                                    <input type="number" class="form-control bg-light border-light" id="bid-amount" 
                                        placeholder="{{ number_format($auction->current_price + 10, 2, '.', '') }}" 
                                        min="{{ $auction->current_price + 0.01 }}" step="0.01">
                                </div>
                                <button type="submit" class="btn btn-gold w-100 py-3 shadow-sm">Place Bid Now</button>
                            </form>
                        @else
                            <div class="alert alert-light border-light mb-4">
                                <i class="fas fa-info-circle me-2 text-primary"></i>
                                Please <a href="{{ route('login') }}" class="text-primary fw-bold">login</a> to place a bid.
                            </div>
                        @endauth
                    @endif

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary flex-fill">
                            <i class="fas fa-heart me-2"></i>Wishlist
                        </button>
                        <button class="btn btn-outline-secondary">
                            <i class="fas fa-share-alt"></i>
                        </button>
                    </div>
                </div>

                </div>

                <!-- Description Card -->
                <div class="card card-elite p-4 border-0 shadow-sm mt-4" data-aos="fade-up">
                    <h4 class="h5 text-dark mb-4 fw-bold">Product Description</h4>
                    <div class="text-secondary lh-lg small">
                        {!! nl2br(e($auction->description)) !!}
                    </div>
                </div>

                <!-- Recent Bids Card -->
                <div class="card card-elite p-4 border-0 shadow-sm mt-4" data-aos="fade-up">
                    <h4 class="h5 text-dark mb-4 fw-bold">Recent Bids</h4>
                    <ul class="list-unstyled mb-0">
                        <li class="text-secondary small py-4 text-center border rounded-3 bg-light">
                            <i class="fas fa-history d-block mb-2 opacity-25 fs-2"></i>
                            No bids placed yet.
                        </li>
                    </ul>
                </div>


            </div>
        </div>



    </div>
</section>

@endsection
