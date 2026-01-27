@extends('website.layouts.app')

@section('title', 'LaraBids | Premier Online Auction Platform')

@section('content')

<!-- Hero -->
<section class="hero-section text-center text-white">
    <div class="container" data-aos="fade-up">
        <span class="badge-elite mb-3 d-block">The Ultimate Bidding Destination</span>
        <h1 class="display-2 fw-bold mb-4">LaraBids: Browse, <br><span class="gold-text">Bid, and Win</span></h1>
        <p class="lead mb-5 mx-auto opacity-75" style="max-width: 700px;">
            Join the premier community for high-value acquisitions. From rare collectibles to everyday electronics,
            experience the power of professional bidding.
        </p>
        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('auctions.index') }}" class="btn btn-gold btn-lg px-5 py-3">Browse Live Bids</a>
            <a href="#how-it-works" class="btn btn-outline-gold btn-lg px-5 py-3">Learn More</a>
        </div>
    </div>
</section>

<!-- Live Auctions -->
<section id="auctions" class="py-5 metrics-overlap">
    <div class="container py-lg-5">
        <div class="row align-items-center mb-5" data-aos="fade-right">
            <div class="col-lg-4">
                <span class="badge-elite mb-1 d-block">Global Opportunities</span>
                <h2 class="display-4 fw-bold">Live Now</h2>
            </div>
            <div class="col-lg-8">
                <div class="d-flex flex-wrap gap-2 justify-content-lg-end mt-3 mt-lg-0">
                    <a href="{{ route('auctions.index') }}" class="category-pill active">All Auctions</a>
                    @foreach($categories as $category)
                    <a href="{{ route('auctions.index', ['category' => $category->slug]) }}" class="category-pill">
                        <i class="{{ $category->icon }} me-2"></i>{{ $category->name }}
                    </a>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="row g-4">
            @foreach($auctions as $index => $auction)
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ ($index + 1) * 100 }}">
                <div class="card card-elite h-100">
                    <div class="position-relative overflow-hidden"
                        style="height: 280px; border-radius: 12px 12px 0 0;">
                        @if($auction->image)
                            <img src="{{ str_starts_with($auction->image, 'http') ? $auction->image : asset('storage/' . $auction->image) }}" class="card-img-top h-100 object-fit-cover" alt="{{ $auction->title }}">
                        @else
                            <img src="https://images.unsplash.com/photo-1523275335684-21481017106d?auto=format&fit=crop&w=1200"
                                class="card-img-top h-100 object-fit-cover" alt="{{ $auction->title }}">
                        @endif
                        <div class="position-absolute top-0 start-0 m-3">
                            <span class="badge bg-gold text-dark">{{ $auction->category->name ?? 'Uncategorized' }}</span>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        @php
                            $now = \Carbon\Carbon::now();
                            $end = \Carbon\Carbon::parse($auction->end_time);
                            $diff = $now->diff($end);
                            $isClosed = $now->greaterThan($end);
                        @endphp
                        
                        @if(!$isClosed)
                        <div class="glass-timer text-center py-2 timer-val mb-3" 
                            data-days="{{ $diff->d }}" 
                            data-hours="{{ $diff->h }}" 
                            data-min="{{ $diff->i }}" 
                            data-sec="{{ $diff->s }}">
                            <div class="row g-0 px-3">
                                <div class="col">
                                    <div class="fw-bold fs-6" data-days>{{ sprintf('%02d', $diff->d) }}</div>
                                    <small class="opacity-50" style="font-size: 0.6rem;">DAYS</small>
                                </div>
                                <div class="col">
                                    <div class="fw-bold fs-6" data-hours>{{ sprintf('%02d', $diff->h) }}</div>
                                    <small class="opacity-50" style="font-size: 0.6rem;">HRS</small>
                                </div>
                                <div class="col">
                                    <div class="fw-bold fs-6" data-min>{{ sprintf('%02d', $diff->i) }}</div>
                                    <small class="opacity-50" style="font-size: 0.6rem;">MIN</small>
                                </div>
                                <div class="col">
                                    <div class="fw-bold fs-6" data-sec>{{ sprintf('%02d', $diff->s) }}</div>
                                    <small class="opacity-50" style="font-size: 0.6rem;">SEC</small>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="alert alert-danger py-2 mb-3 text-center small">Closed</div>
                        @endif

                        <h3 class="h5 mb-2">{{ $auction->title }}</h3>
                        <p class="text-secondary small mb-4 line-clamp-2">{{ Str::limit($auction->description, 60) }}</p>
                        <div class="d-flex justify-content-between align-items-center mb-4 pt-3 border-top border-white border-opacity-10">
                            <span class="small text-secondary">CURRENT BID</span>
                            <span class="h5 mb-0 text-gold fw-bold" style="color: var(--elite-gold);">${{ number_format($auction->current_price, 2) }}</span>
                        </div>
                        <a href="{{ route('auctions.show', $auction->id) }}" class="btn btn-gold w-100 py-2">Submit Bid</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>


<!-- How LaraBids Works -->
<section id="how-it-works" class="py-5 bg-dark-elite">
    <div class="container py-lg-5">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="badge-elite mb-2 d-inline-block">The Process</span>
            <h2 class="display-4 fw-bold">How <span class="gold-text">LaraBids</span> Works</h2>
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
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="glass-panel p-5 text-center" data-aos="zoom-in">
                    <i class="fas fa-quote-left text-gold fs-1 mb-4 opacity-25"></i>
                    <p class="h4 display-font text-white mb-4 lh-base">
                        "LaraBids redefined my concept of online auctions. The transparency of the platform
                        and the ease of bidding are simply unmatched in the industry."
                    </p>
                    <div class="d-flex align-items-center justify-content-center gap-3">
                        <img src="https://ui-avatars.com/api/?name=James+Roth&background=d4af37&color=0a192f"
                            class="rounded-circle border border-gold border-opacity-25" width="50" alt="Client">
                        <div class="text-start">
                            <h6 class="text-gold mb-0">James Rothwell</h6>
                            <small class="text-secondary">Private Collector</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Upcoming Auctions -->
<section class="py-5 bg-navy-shade">
    <div class="container py-lg-5">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="badge-elite mb-2 d-inline-block">Watch This Space</span>
            <h2 class="display-4 fw-bold">Upcoming Auctions</h2>
        </div>
        <div class="row g-4">
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="card card-elite glass-panel border-0">
                    <div class="p-4">
                        <h5 class="text-gold mb-1">Modernism in Kyoto</h5>
                        <p class="small text-secondary mb-3">Architectural Masterpiece Collection</p>
                        <div class="d-flex align-items-center gap-2 mb-0">
                            <i class="far fa-calendar text-gold"></i>
                            <span class="small text-white">Starts Feb 14, 2026</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="card card-elite glass-panel border-0">
                    <div class="p-4">
                        <h5 class="text-white mb-1">The Heritage Chronos</h5>
                        <p class="small text-secondary mb-3">50 Rare Timepieces from 1920-1950</p>
                        <div class="d-flex align-items-center gap-2 mb-0">
                            <i class="far fa-calendar text-gold"></i>
                            <span class="small text-white">Starts Mar 02, 2026</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
                <div class="card card-elite glass-panel border-0">
                    <div class="p-4">
                        <h5 class="text-white mb-1">Abstract Expressionism</h5>
                        <p class="small text-secondary mb-3">Post-War American Art Auction</p>
                        <div class="d-flex align-items-center gap-2 mb-0">
                            <i class="far fa-calendar text-gold"></i>
                            <span class="small text-white">Starts Mar 10, 2026</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Trusted Partners -->
<section class="py-5 border-top border-white border-opacity-5">
    <div class="container py-4">
        <div class="row g-4 align-items-center justify-content-center opacity-50 px-lg-5">
            <div class="col-6 col-md-2 text-center" data-aos="fade-in" data-aos-delay="100"><i
                    class="fab fa-fedex fs-1 text-white"></i></div>
            <div class="col-6 col-md-2 text-center" data-aos="fade-in" data-aos-delay="200"><i
                    class="fab fa-ups fs-1 text-white"></i></div>
            <div class="col-6 col-md-2 text-center" data-aos="fade-in" data-aos-delay="300"><i
                    class="fab fa-dhl fs-1 text-white"></i></div>
            <div class="col-6 col-md-2 text-center" data-aos="fade-in" data-aos-delay="400"><i
                    class="fab fa-stripe fs-1 text-white"></i></div>
            <div class="col-6 col-md-2 text-center" data-aos="fade-in" data-aos-delay="500"><i
                    class="fab fa-apple-pay fs-1 text-white"></i></div>
        </div>
    </div>
</section>

@endsection
