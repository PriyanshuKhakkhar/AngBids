@extends('website.layouts.app')

@section('title', 'Auction Details | LaraBids')

@section('content')

<!-- Breadcrumb -->
<section class="breadcrumb-elite text-center text-white py-5">
    <div class="container">
        <h1 class="display-4 fw-bold mb-3">Auction <span class="gold-text">Details</span></h1>
        <p class="lead opacity-75">Item #{{ $id }}</p>
    </div>
</section>

<!-- Auction Details -->
<section class="py-5">
    <div class="container py-lg-5">
        <div class="row g-5">
            <!-- Image Gallery -->
            <div class="col-lg-6" data-aos="fade-right">
                <div class="position-relative overflow-hidden rounded-3 mb-3" style="height: 500px;">
                    <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=1200"
                        class="w-100 h-100 object-fit-cover" alt="Auction Item">
                </div>
            </div>

            <!-- Auction Info -->
            <div class="col-lg-6" data-aos="fade-left">
                <div class="glass-panel p-4 mb-4">
                    <h2 class="h3 text-white mb-3">Premium Luxury Watch</h2>
                    <p class="text-secondary mb-4">Rare 18k Rose Gold Perpetual Calendar Chronograph</p>

                    <!-- Timer -->
                    <div class="glass-timer text-center py-3 timer-val mb-4" 
                        data-days="02" data-hours="14" data-min="45" data-sec="12">
                        <div class="row g-0 px-3">
                            <div class="col">
                                <div class="fw-bold fs-4" data-days>02</div>
                                <small class="opacity-50">DAYS</small>
                            </div>
                            <div class="col">
                                <div class="fw-bold fs-4" data-hours>14</div>
                                <small class="opacity-50">HRS</small>
                            </div>
                            <div class="col">
                                <div class="fw-bold fs-4" data-min>45</div>
                                <small class="opacity-50">MIN</small>
                            </div>
                            <div class="col">
                                <div class="fw-bold fs-4" data-sec>12</div>
                                <small class="opacity-50">SEC</small>
                            </div>
                        </div>
                    </div>

                    <!-- Current Bid -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-secondary">Current Bid</span>
                            <span class="h3 mb-0 gold-text fw-bold">$45,200</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-secondary small">Starting Bid</span>
                            <span class="text-white small">$40,000</span>
                        </div>
                    </div>

                    <!-- Bid Form -->
                    @auth
                        <form class="mb-4">
                            <label for="bid-amount" class="form-label text-white">Your Bid Amount</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text bg-dark text-gold border-gold">$</span>
                                <input type="number" class="form-control form-control-elite" id="bid-amount" 
                                    placeholder="45,300" min="45300">
                            </div>
                            <button type="submit" class="btn btn-gold w-100 py-3">Place Bid</button>
                        </form>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-info-circle me-2"></i>
                            Please <a href="{{ route('login') }}" class="text-decoration-underline">login</a> to place a bid.
                        </div>
                    @endauth

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
                        <li class="mb-2"><strong class="text-white">Condition:</strong> Excellent</li>
                        <li class="mb-2"><strong class="text-white">Category:</strong> Watches</li>
                        <li class="mb-2"><strong class="text-white">Brand:</strong> Premium Luxury</li>
                        <li class="mb-2"><strong class="text-white">Year:</strong> 2022</li>
                        <li class="mb-2"><strong class="text-white">Location:</strong> New York, USA</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Description & Bid History -->
        <div class="row g-5 mt-4">
            <div class="col-lg-8">
                <div class="glass-panel p-4" data-aos="fade-up">
                    <h4 class="h5 text-white mb-3">Description</h4>
                    <p class="text-secondary">
                        This exceptional timepiece represents the pinnacle of horological craftsmanship. 
                        Featuring a perpetual calendar complication and chronograph function, this watch 
                        is crafted from 18k rose gold and showcases meticulous attention to detail.
                    </p>
                    <p class="text-secondary">
                        The watch comes with original box, papers, and certificate of authenticity. 
                        It has been professionally serviced and is in excellent working condition.
                    </p>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="glass-panel p-4" data-aos="fade-up" data-aos-delay="100">
                    <h4 class="h5 text-white mb-3">Recent Bids</h4>
                    <ul class="list-unstyled">
                        <li class="mb-3 pb-3 border-bottom border-white border-opacity-10">
                            <div class="d-flex justify-content-between">
                                <span class="text-white small">User***123</span>
                                <span class="text-gold small fw-bold">$45,200</span>
                            </div>
                            <small class="text-secondary">2 minutes ago</small>
                        </li>
                        <li class="mb-3 pb-3 border-bottom border-white border-opacity-10">
                            <div class="d-flex justify-content-between">
                                <span class="text-white small">Bidder***456</span>
                                <span class="text-gold small fw-bold">$45,000</span>
                            </div>
                            <small class="text-secondary">15 minutes ago</small>
                        </li>
                        <li class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-white small">Collector***789</span>
                                <span class="text-gold small fw-bold">$44,500</span>
                            </div>
                            <small class="text-secondary">1 hour ago</small>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
