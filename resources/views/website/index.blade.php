@extends('website.layouts.app')

@section('title', 'LaraBids | Premier Online Auction Platform')

@section('content')

<!-- Hero Carousel -->
<section id="heroCarousel" class="carousel slide hero-section" data-bs-ride="carousel">
    <!-- Indicators -->
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
    </div>

    <div class="carousel-inner">
        <!-- Slide 1 (Original) -->
        <div class="carousel-item active">
            <div class="container h-100 d-flex align-items-center" data-aos="fade-up">
                <div class="row align-items-center w-100">
                    <div class="col-lg-6 text-center text-lg-start mb-5 mb-lg-0">
                        <h1 class="display-2 fw-bold mb-4">LaraBids: Browse, <br><span class="text-white">Bid, and Win</span></h1>
                        <p class="lead mb-5 opacity-75 pe-lg-5">
                            Join the premier community for high-value acquisitions. From rare collectibles to everyday electronics,
                            experience the power of professional bidding.
                        </p>
                        <div class="d-flex flex-wrap justify-content-center justify-content-lg-start gap-3">
                            <a href="{{ route('auctions.index') }}" class="btn btn-gold btn-lg px-5 py-3 rounded-pill shadow">Browse Live Bids</a>
                            <a href="#how-it-works" class="btn btn-outline-gold btn-lg px-5 py-3 rounded-pill">Learn More</a>
                        </div>
                    </div>
                    <div class="col-lg-6 d-none d-lg-block">
                        <div class="hero-illustration-wrapper ps-lg-5 text-center">
                            <div class="hero-glow-blob"></div>
                            <img src="{{ asset('assets/images/banner-3.png') }}" class="img-fluid" alt="LaraBids Premium Auctions" style="max-height: 500px; filter: drop-shadow(0 20px 50px rgba(0,0,0,0.3));">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide 2 -->
        <div class="carousel-item">
            <div class="container h-100 d-flex align-items-center">
                <div class="row align-items-center w-100">
                    <div class="col-lg-6 text-center text-lg-start mb-5 mb-lg-0">
                        <h2 class="display-2 fw-bold mb-4 text-white">Rare Treasures <br><span>Await You</span></h2>
                        <p class="lead mb-5 opacity-75 pe-lg-5">
                            Find unique items that aren't available anywhere else. Our verified sellers bring you the best in luxury and rarity.
                        </p>
                        <div class="d-flex flex-wrap justify-content-center justify-content-lg-start gap-3">
                            <a href="{{ route('auctions.create') }}" class="btn btn-gold btn-lg px-5 py-3 rounded-pill shadow">Start Selling</a>
                            <a href="{{ route('register') }}" class="btn btn-outline-gold btn-lg px-5 py-3 rounded-pill">Join Now</a>
                        </div>
                    </div>
                    <div class="col-lg-6 d-none d-lg-block">
                        <div class="hero-illustration-wrapper ps-lg-5 text-center">
                            <div class="hero-glow-blob" style="background: rgba(255, 215, 0, 0.2);"></div>
                            <img src="{{ asset('assets/images/banner-2.png') }}" class="img-fluid" alt="LaraBids Rare Items" style="max-height: 500px; filter: drop-shadow(0 20px 50px rgba(0,0,0,0.3));">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide 3 -->
        <div class="carousel-item">
            <div class="container h-100 d-flex align-items-center">
                <div class="row align-items-center w-100">
                    <div class="col-lg-6 text-center text-lg-start mb-5 mb-lg-0">
                        <h2 class="display-2 fw-bold mb-4 text-white">Bid with <br><span>Confidence</span></h2>
                        <p class="lead mb-5 opacity-75 pe-lg-5">
                            Every bid is tracked and Every seller is verified. Experience the most transparent auction platform in India.
                        </p>
                        <div class="d-flex flex-wrap justify-content-center justify-content-lg-start gap-3">
                            <a href="{{ route('about') }}" class="btn btn-gold btn-lg px-5 py-3 rounded-pill shadow">How it works</a>
                            <a href="{{ route('contact') }}" class="btn btn-outline-gold btn-lg px-5 py-3 rounded-pill">Contact Us</a>
                        </div>
                    </div>
                    <div class="col-lg-6 d-none d-lg-block">
                        <div class="hero-illustration-wrapper ps-lg-5 text-center">
                            <div class="hero-glow-blob" style="background: rgba(0, 255, 127, 0.2);"></div>
                            <img src="{{ asset('assets/images/banner-3.png') }}" class="img-fluid" alt="LaraBids Secure" style="max-height: 500px; filter: drop-shadow(0 20px 50px rgba(0,0,0,0.3));">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Carousel Controls -->
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</section>


<!-- Live Auctions -->
<section id="auctions" class="pt-5 pb-0">
    <div class="container pt-lg-5 pb-0">
        <div class="row align-items-end mb-5" data-aos="fade-right">
            <div class="col-lg-6">
                <h2 class="display-4 fw-bold mb-3">Live Now</h2>
                <p class="text-secondary lead mb-0">Explore our most popular auctions currently open for bidding.</p>
            </div>
            <div class="col-lg-6 text-lg-end">
                <a href="{{ route('auctions.index') }}" class="btn btn-outline-primary rounded-pill px-4 py-2 fw-bold shadow-sm mt-3 mt-lg-0">
                    View All Auctions <i class="fas fa-chevron-right ms-2"></i>
                </a>
            </div>
        </div>

        <div class="row g-4">
            @foreach($auctions as $index => $auction)
            <div class="col-md-6 col-lg-3">
                <div class="card card-elite h-100 position-relative shadow-sm border-0 rounded-4 overflow-hidden bg-white hover-shadow-lg transition-all">
                    <a href="{{ route('auctions.show', $auction->id) }}" class="stretched-link"></a>
                    <!-- Image Section -->
                    <div class="position-relative overflow-hidden" style="height: 180px;">
                        <div class="d-block w-100 h-100">
                            @if($auction->image)
                                <img src="{{ str_starts_with($auction->image, 'http') ? $auction->image : asset('storage/' . $auction->image) }}" class="card-img-top h-100 object-fit-cover shadow-sm transition-all" alt="{{ $auction->title }}">
                            @else
                                <img src="https://images.unsplash.com/photo-1523275335684-21481017106d?auto=format&fit=crop&w=1200"
                                    class="card-img-top h-100 object-fit-cover shadow-sm transition-all" alt="{{ $auction->title }}">
                            @endif
                        </div>
                        <div class="position-absolute top-0 start-0 m-2" style="z-index: 2;">
                            <span class="badge bg-gold text-dark shadow-sm fw-bold" style="font-size: 0.7rem;">{{ $auction->category->name ?? 'Uncategorized' }}</span>
                        </div>
                        <div class="position-absolute top-0 end-0 m-2" style="z-index: 2;">
                            <form action="{{ route('user.watchlist.toggle', $auction->id ?? 0) }}" method="POST" class="watchlist-toggle-form">
                                @csrf
                                <button type="submit" class="btn btn-white rounded-circle shadow-sm d-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px; border: none; background: rgba(255,255,255,0.8); backdrop-filter: blur(4px);">
                                    <i class="{{ $auction->watchlists->isNotEmpty() ? 'fas' : 'far' }} fa-heart text-danger" style="font-size: 0.8rem;"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Content Section -->
                    <div class="card-body p-3 d-flex flex-column flex-grow-1">
                        @php
                            $now = \Carbon\Carbon::now();
                            $end = \Carbon\Carbon::parse($auction->end_time);
                            $diff = $now->diff($end);
                            $isClosed = $now->greaterThan($end);
                        @endphp
                        
                        @if(!$isClosed)
                        <div class="glass-timer text-center py-1 timer-val mb-2 shadow-none border {{ ($diff->d == 0 && $diff->h == 0) ? 'urgent-timer' : '' }}" 
                            data-days="{{ $diff->d }}" 
                            data-hours="{{ $diff->h }}" 
                            data-min="{{ $diff->i }}" 
                            data-sec="{{ $diff->s }}">
                            <div class="row g-0 px-2">
                                <div class="col border-end border-light">
                                    <div class="fw-bold fs-7" data-days>{{ sprintf('%02d', $diff->d) }}</div>
                                    <small class="opacity-50 text-uppercase d-block" style="font-size: 0.5rem;">D</small>
                                </div>
                                <div class="col border-end border-light">
                                    <div class="fw-bold fs-7" data-hours>{{ sprintf('%02d', $diff->h) }}</div>
                                    <small class="opacity-50 text-uppercase d-block" style="font-size: 0.5rem;">H</small>
                                </div>
                                <div class="col border-end border-light">
                                    <div class="fw-bold fs-7" data-min>{{ sprintf('%02d', $diff->i) }}</div>
                                    <small class="opacity-50 text-uppercase d-block" style="font-size: 0.5rem;">M</small>
                                </div>
                                <div class="col">
                                    <div class="fw-bold fs-7 text-primary" data-sec>{{ sprintf('%02d', $diff->s) }}</div>
                                    <small class="opacity-50 text-uppercase d-block" style="font-size: 0.5rem;">S</small>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="alert alert-danger alert-permanent border-0 py-1 mb-2 text-center small fw-bold" style="font-size: 0.7rem; background: rgba(220, 53, 69, 0.1); color: #dc3545;">
                            <i class="fas fa-times-circle me-1"></i> Auction Closed
                        </div>
                        @endif

                        <h3 class="h6 mb-2 fw-bold text-dark text-truncate title-hover">
                            {{ $auction->title }}
                        </h3>
                        
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="d-flex align-items-center">
                                @if($auction->user && $auction->user->avatar)
                                    <img src="{{ str_starts_with($auction->user->avatar, 'http') ? $auction->user->avatar : asset('storage/' . $auction->user->avatar) }}" class="rounded-circle me-1 border" width="20" height="20" style="object-fit: cover;" alt="{{ $auction->user->name }}">
                                @else
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-1 border" style="width: 20px; height: 20px;">
                                        <i class="fas fa-user text-secondary" style="font-size: 10px;"></i>
                                    </div>
                                @endif
                                <span class="text-xs text-muted text-truncate" style="max-width: 80px;">{{ $auction->user->name ?? 'Seller' }}</span>
                            </div>
                            <span class="badge bg-light text-secondary border fw-normal text-xs px-2 py-1">
                                {{ $auction->bids->count() }} Bids
                            </span>
                        </div>
                        
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center mb-2 pt-2 border-top">
                                <span class="text-xs text-secondary fw-bold text-uppercase">{{ $isClosed ? 'Final Bid' : 'Current Bid' }}</span>
                                <span class="h6 mb-0 text-primary fw-bold">₹{{ number_format($auction->current_price, 2) }}</span>
                            </div>
                            <div class="btn {{ $isClosed ? 'btn-outline-secondary' : 'btn-primary' }} w-100 py-2 rounded-pill fw-bold shadow-sm transition-all btn-hover-effect" style="font-size: 0.8rem;">
                                @if($isClosed) CLOSED @else BID NOW @endif <i class="fas {{ $isClosed ? 'fa-lock' : 'fa-gavel' }} ms-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Upcoming Auctions -->
<section class="py-4 bg-navy-shade">
    <div class="container py-lg-4">
        <div class="row align-items-end mb-5" data-aos="fade-up">
            <div class="col-lg-6 text-center text-lg-start">
                <h2 class="display-4 fw-bold mb-0">Upcoming Auctions</h2>
            </div>
            <div class="col-lg-6 text-center text-lg-end">
                <a href="{{ route('auctions.index', ['status' => 'upcoming']) }}" class="btn btn-outline-primary rounded-pill px-4 py-2 fw-bold shadow-sm mt-3 mt-lg-0">
                    View All Upcoming <i class="fas fa-chevron-right ms-2"></i>
                </a>
            </div>
        </div>
        @if(count($upcomingAuctions) > 0)
        <div class="position-relative">
            <div class="swiper upcoming-swiper px-3">
                <div class="swiper-wrapper">
                    @foreach($upcomingAuctions as $upcoming)
                    <div class="swiper-slide h-auto">
                        <div class="card card-elite h-100 position-relative shadow-sm border-0 rounded-4 overflow-hidden bg-white hover-shadow-lg transition-all">
                            <a href="{{ route('auctions.show', $upcoming->id) }}" class="stretched-link"></a>
                            <!-- Image Section -->
                            <div class="position-relative overflow-hidden" style="height: 180px;">
                                <div class="d-block w-100 h-100">
                                    @if($upcoming->image)
                                        <img src="{{ str_starts_with($upcoming->image, 'http') ? $upcoming->image : asset('storage/' . $upcoming->image) }}" class="card-img-top h-100 object-fit-cover shadow-sm transition-all" alt="{{ $upcoming->title }}">
                                    @else
                                        <img src="https://images.unsplash.com/photo-1523275335684-21481017106d?auto=format&fit=crop&w=1200"
                                            class="card-img-top h-100 object-fit-cover shadow-sm transition-all" alt="{{ $upcoming->title }}">
                                    @endif
                                </div>
                                <div class="position-absolute top-0 start-0 m-2" style="z-index: 2;">
                                    <span class="badge bg-gold text-dark shadow-sm fw-bold" style="font-size: 0.7rem;">{{ $upcoming->category->name ?? 'Uncategorized' }}</span>
                                </div>
                                <div class="position-absolute top-0 end-0 m-2" style="z-index: 2;">
                                    <form action="{{ route('user.watchlist.toggle', $upcoming->id ?? 0) }}" method="POST" class="watchlist-toggle-form">
                                        @csrf
                                        <button type="submit" class="btn btn-white rounded-circle shadow-sm d-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px; border: none; background: rgba(255,255,255,0.8); backdrop-filter: blur(4px);">
                                            <i class="{{ $upcoming->watchlists->isNotEmpty() ? 'fas' : 'far' }} fa-heart text-danger" style="font-size: 0.8rem;"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Content Section -->
                            <div class="card-body p-3 d-flex flex-column flex-grow-1">
                                @php
                                    $now = \Carbon\Carbon::now();
                                    $startTime = \Carbon\Carbon::parse($upcoming->start_time);
                                    $diff = $now->diff($startTime);
                                @endphp
                                
                                <div class="alert alert-info alert-permanent py-1 mb-2 text-center small border-0 fw-bold" style="font-size: 0.7rem; background: rgba(13, 202, 240, 0.1); color: #0dcaf0;">
                                    <i class="fas fa-clock me-1"></i> Starts {{ $startTime->format('M d, H:i') }}
                                </div>

                                <h3 class="h6 mb-2 fw-bold text-dark text-truncate title-hover">
                                    {{ $upcoming->title }}
                                </h3>
                                
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="d-flex align-items-center">
                                        @if($upcoming->user && $upcoming->user->avatar)
                                            <img src="{{ str_starts_with($upcoming->user->avatar, 'http') ? $upcoming->user->avatar : asset('storage/' . $upcoming->user->avatar) }}" class="rounded-circle me-1 border" width="20" height="20" style="object-fit: cover;" alt="{{ $upcoming->user->name }}">
                                        @else
                                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-1 border" style="width: 20px; height: 20px;">
                                                <i class="fas fa-user text-secondary" style="font-size: 10px;"></i>
                                            </div>
                                        @endif
                                        <span class="text-xs text-muted text-truncate" style="max-width: 80px;">{{ $upcoming->user->name ?? 'Seller' }}</span>
                                    </div>
                                    <span class="badge bg-light text-secondary border fw-normal text-xs px-2 py-1">
                                        {{ $upcoming->bids->count() }} Bids
                                    </span>
                                </div>
                                
                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between align-items-center mb-2 pt-2 border-top">
                                        <span class="text-xs text-secondary fw-bold text-uppercase">Current Bid</span>
                                        <span class="h6 mb-0 text-primary fw-bold">₹{{ number_format($upcoming->current_price, 2) }}</span>
                                    </div>
                                    <div class="btn btn-primary w-100 py-2 rounded-pill fw-bold shadow-sm transition-all btn-hover-effect" style="font-size: 0.8rem;">
                                        VIEW <i class="fas fa-gavel ms-1"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <!-- Navigation Buttons -->
            <div class="swiper-button-next upcoming-next"></div>
            <div class="swiper-button-prev upcoming-prev"></div>
        </div>
        @else
        <div class="text-center py-5">
            <div class="opacity-50 mb-3">
                <i class="fas fa-calendar-times fa-3x text-secondary"></i>
            </div>
            <h5 class="text-secondary">No upcoming auctions scheduled yet.</h5>
            <p class="small text-muted">Stay tuned for amazing deals!</p>
        </div>
        @endif
    </div>
</section>


<!-- How LaraBids Works -->
<section id="how-it-works" class="py-5 bg-dark-elite">
    <div class="container py-lg-5">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="display-4 fw-bold text-white">How <span class="text-white">LaraBids</span> Works</h2>
        </div>
        <div class="row g-4 step-connector">
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="step-card text-center p-4">
                    <div class="step-number mb-4 mx-auto">01</div>
                    <h4 class="h5 text-white mb-3">Quick Registration</h4>
                    <p class="text-secondary small">Create your account and verify your email to join our
                        global community of bidders.</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="step-card text-center p-4">
                    <div class="step-number mb-4 mx-auto">02</div>
                    <h4 class="h5 text-white mb-3">Place Your Bid</h4>
                    <p class="text-secondary small">Engage in real-time bidding for verified items with transparent
                        history and real-time alerts.</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                <div class="step-card text-center p-4">
                    <div class="step-number mb-4 mx-auto">03</div>
                    <h4 class="h5 text-white mb-3">Secure Delivery</h4>
                    <p class="text-secondary small">Upon winning, enjoy secure transaction
                        handling and reliable delivery for your new item.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Client Testimonials -->
<section class="py-5">
    <div class="container py-lg-5">
        <!-- Section Header -->
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="display-4 fw-bold">What Our <span class="text-primary">Clients Say</span></h2>
            <p class="lead text-secondary mt-3">Hear from satisfied bidders and sellers in our community</p>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
                    <!-- Carousel Indicators -->
                    <div class="carousel-indicators mb-n4">
                        @foreach($testimonials as $index => $testimonial)
                        <button type="button" data-bs-target="#testimonialCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }} bg-primary" aria-current="{{ $index == 0 ? 'true' : 'false' }}" aria-label="Slide {{ $index + 1 }}"></button>
                        @endforeach
                    </div>

                    <div class="carousel-inner overflow-visible">
                        @foreach($testimonials as $index => $testimonial)
                        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                            <div class="glass-panel-premium p-5 text-center mx-3 my-4">
                                <div class="mb-4">
                                    @for($i = 0; $i < 5; $i++)
                                    <i class="fas fa-star text-warning small"></i>
                                    @endfor
                                </div>
                                <i class="fas fa-quote-left text-primary fs-1 mb-4 opacity-25"></i>
                                <p class="h4 display-font text-dark mb-4 lh-base fw-semibold px-lg-5">
                                    "{{ $testimonial->content }}"
                                </p>
                                <div class="d-flex align-items-center justify-content-center gap-3">
                                    <div class="position-relative">
                                        <img src="{{ $testimonial->avatar_url }}"
                                            class="rounded-circle border border-primary border-2 shadow-sm" width="60" height="60" alt="{{ $testimonial->name }}" style="object-fit: cover;">
                                        <div class="position-absolute bottom-0 end-0 bg-success border border-white rounded-circle" style="width: 14px; height: 14px; border-width: 2px !important;"></div>
                                    </div>
                                    <div class="text-start">
                                        <h6 class="text-primary mb-0 fw-bold">{{ $testimonial->name }}</h6>
                                        <small class="text-secondary opacity-75">{{ $testimonial->role }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    @if($testimonials->count() > 1)
                    <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon bg-primary rounded-circle p-3" aria-hidden="true" style="background-size: 50%;"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon bg-primary rounded-circle p-3" aria-hidden="true" style="background-size: 50%;"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Promotional Banner Section -->
<section class="py-5 bg-dark-elite position-relative overflow-hidden">
    <div class="container py-lg-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-6" data-aos="fade-right">
                @guest
                <h2 class="display-4 fw-bold text-white mb-4">
                    Ready to Place Your First Bid?
                </h2>
                <p class="lead text-white opacity-75 mb-4">
                    Join thousands of successful bidders on LaraBids. Whether you're hunting for rare collectibles, 
                    premium electronics, or unique treasures, your next win is just a bid away.
                </p>
                @else
                <h2 class="display-4 fw-bold text-white mb-4">
                    Find Your Next Treasure
                </h2>
                <p class="lead text-white opacity-75 mb-4">
                    Welcome back, {{ Auth::user()->name }}! Explore our latest auctions and continue your winning streak. 
                    From rare collectibles to premium electronics, your next win awaits.
                </p>
                @endguest
                
                <ul class="list-unstyled mb-4">
                    <li class="mb-3 text-white">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <strong>Verified Sellers</strong> - All items authenticated
                    </li>
                    <li class="mb-3 text-white">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <strong>Secure Payments</strong> - Protected transactions
                    </li>
                    <li class="mb-3 text-white">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <strong>Real-time Bidding</strong> - Never miss an opportunity
                    </li>
                </ul>
                
                <div class="d-flex flex-wrap gap-3">
                    @guest
                    <a href="{{ route('register') }}" class="btn btn-gold btn-lg px-5 py-3 rounded-pill shadow">
                        <i class="fas fa-user-plus me-2"></i>Create Free Account
                    </a>
                    <a href="{{ route('auctions.index') }}" class="btn btn-outline-gold btn-lg px-5 py-3 rounded-pill">
                        <i class="fas fa-gavel me-2"></i>Browse Auctions
                    </a>
                    @else
                    <a href="{{ route('auctions.index') }}" class="btn btn-gold btn-lg px-5 py-3 rounded-pill shadow">
                        <i class="fas fa-gavel me-2"></i>Browse Live Auctions
                    </a>
                    <a href="{{ route('auctions.create') }}" class="btn btn-outline-gold btn-lg px-5 py-3 rounded-pill">
                        <i class="fas fa-plus-circle me-2"></i>Create Auction
                    </a>
                    @endguest
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <div class="position-relative">
                    <div class="hero-glow-blob"></div>
                    <img src="{{ asset('assets/images/banner-2.png') }}" 
                         class="img-fluid" 
                         alt="Start Bidding on LaraBids"
                         style="max-height: 500px; filter: drop-shadow(0 20px 50px rgba(0,0,0,0.3));">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Trusted Partners -->
<section class="py-5 border-top border-light">
    <div class="container py-2">
        <div class="row g-4 align-items-center justify-content-center opacity-75 px-lg-5">
            <div class="col-6 col-md-2 text-center" data-aos="fade-in" data-aos-delay="100"><i
                    class="fab fa-fedex fs-1 text-secondary partner-logo"></i></div>
            <div class="col-6 col-md-2 text-center" data-aos="fade-in" data-aos-delay="200"><i
                    class="fab fa-ups fs-1 text-secondary partner-logo"></i></div>
            <div class="col-6 col-md-2 text-center" data-aos="fade-in" data-aos-delay="300"><i
                    class="fab fa-dhl fs-1 text-secondary partner-logo"></i></div>
            <div class="col-6 col-md-2 text-center" data-aos="fade-in" data-aos-delay="400"><i
                    class="fab fa-stripe fs-1 text-secondary partner-logo"></i></div>
            <div class="col-6 col-md-2 text-center" data-aos="fade-in" data-aos-delay="500"><i
                    class="fab fa-apple-pay fs-1 text-secondary partner-logo"></i></div>

        </div>
    </div>
</section>


@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
    .bg-navy-shade {
        background-color: #f8f9fc;
    }
    .card-elite {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .card-elite:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .bg-gold {
        background-color: #d4af37;
    }
    .glass-timer {
        background: rgba(248, 249, 250, 0.8);
        backdrop-filter: blur(4px);
        border-radius: 8px;
    }
    .fs-7 {
        font-size: 0.9rem;
    }
    .text-primary {
        color: #4e73df !important;
    }
    .text-xs {
        font-size: 0.75rem;
    }
    .urgent-timer {
        background: rgba(220, 53, 69, 0.08) !important;
        border-color: rgba(220, 53, 69, 0.3) !important;
    }
    .urgent-timer * {
        color: #dc3545 !important;
    }
    .title-hover:hover {
        color: #4e73df !important;
    }
    .btn-hover-effect:active {
        transform: scale(0.98);
    }

    /* Swiper Slider Styles */
    .upcoming-swiper {
        width: 100%;
        overflow: hidden;
    }
    .swiper-wrapper {
        display: flex !important;
        flex-direction: row !important;
    }
    .upcoming-next, .upcoming-prev {
        background: #fff;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        color: #4e73df !important;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        z-index: 10;
        transition: all 0.3s ease;
    }
    .upcoming-next:after, .upcoming-prev:after {
        font-size: 1.1rem;
        font-weight: bold;
    }
    .upcoming-next { right: -50px; }
    .upcoming-prev { left: -50px; }

    @media (max-width: 1400px) {
        .upcoming-next { right: -25px; }
        .upcoming-prev { left: -25px; }
    }

    @media (max-width: 1200px) {
        .upcoming-next { right: 10px; }
        .upcoming-prev { left: 10px; }
    }
</style>

@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Countdown functionality
    const timerBoxes = document.querySelectorAll('.timer-val');
    
    setInterval(() => {
        timerBoxes.forEach(box => {
            let d = parseInt(box.dataset.days);
            let h = parseInt(box.dataset.hours);
            let m = parseInt(box.dataset.min);
            let s = parseInt(box.dataset.sec);

            if (d === 0 && h === 0 && m === 0 && s === 0) return;

            s--;
            if (s < 0) { s = 59; m--; }
            if (m < 0) { m = 59; h--; }
            if (h < 0) { h = 23; d--; }

            box.dataset.days = d;
            box.dataset.hours = h;
            box.dataset.min = m;
            box.dataset.sec = s;

            // Update text content safely
            const dElem = box.querySelector('[data-days]');
            const hElem = box.querySelector('[data-hours]');
            const mElem = box.querySelector('[data-min]');
            const sElem = box.querySelector('[data-sec]');

            if(dElem) dElem.innerText = d.toString().padStart(2, '0');
            if(hElem) hElem.innerText = h.toString().padStart(2, '0');
            if(mElem) mElem.innerText = m.toString().padStart(2, '0');
            if(sElem) sElem.innerText = s.toString().padStart(2, '0');

            // Color urgency logic: < 1 hour (0 days, 0 hours) -> Red Box
            if (d === 0 && h === 0) {
                box.classList.add('urgent-timer');
            } else {
                box.classList.remove('urgent-timer');
            }
        });
    }, 1000);

    var myCarousel = document.getElementById('testimonialCarousel');
    if (myCarousel) {
        // Initialize the carousel
        var carousel = new bootstrap.Carousel(myCarousel, {
            interval: 4000,
            pause: 'hover',
            ride: 'carousel',
            wrap: true
        });
        
        // Force start cycling
        carousel.cycle();
        
        console.log('Testimonials carousel initialized and cycle forced.');
    }

    // Initialize Swiper for Upcoming Auctions
    if (document.querySelector('.upcoming-swiper')) {
        const swiper = new Swiper('.upcoming-swiper', {
            slidesPerView: 1,
            spaceBetween: 25,
            loop: true,
            slidesPerGroup: 1,
            grabCursor: true,
            navigation: {
                nextEl: '.upcoming-next',
                prevEl: '.upcoming-prev',
            },
            breakpoints: {
                576: { slidesPerView: 2 },
                992: { slidesPerView: 4 }
            }
        });
    }
});
</script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

@endpush
@endsection

