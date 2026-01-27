@extends('website.layouts.app')

@section('title', 'All Auctions | LaraBids')

@section('content')

<!-- Breadcrumb -->
<section class="breadcrumb-elite text-center text-white py-5">
    <div class="container">
        <h1 class="display-3 fw-bold mb-3">Browse <span class="gold-text">Auctions</span></h1>
        <p class="lead opacity-75">Discover Amazing Items Up for Bid</p>
    </div>
</section>

<!-- Auctions Listing -->
<section class="py-5">
    <div class="container py-lg-5">
        <!-- Search Bar -->
        <div class="row justify-content-center mb-5">
            <div class="col-md-8 col-lg-6">
                <form action="{{ route('auctions.index') }}" method="GET" class="search-form-elite">
                    <div class="input-group">
                        <input type="text" name="q" class="form-control bg-dark text-white border-gold border-opacity-25" 
                            placeholder="Search for auctions..." value="{{ request('q') }}">
                        @if(request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        <button class="btn btn-gold px-4" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Category Filters -->
        <div class="d-flex flex-wrap gap-2 justify-content-center mb-5" data-aos="fade-up">
            <a href="{{ route('auctions.index', request()->only(['q'])) }}" 
               class="category-pill {{ !request('category') ? 'active' : '' }}">All Auctions</a>
            @foreach($categories as $category)
            <a href="{{ route('auctions.index', array_merge(request()->only(['q']), ['category' => $category->slug])) }}" 
               class="category-pill {{ request('category') == $category->slug ? 'active' : '' }}">
                <i class="{{ $category->icon }} me-2"></i>{{ $category->name }}
            </a>
            @endforeach
        </div>

        <!-- Auction Grid -->
        <div class="row g-4">
            @forelse($auctions as $auction)
            <div class="col-md-6 col-lg-4" data-aos="fade-up">
                <div class="card card-elite h-100">
                    <div class="position-relative overflow-hidden" style="height: 280px; border-radius: 12px 12px 0 0;">
                        @if($auction->image)
                            <img src="{{ asset('storage/' . $auction->image) }}" class="card-img-top h-100 object-fit-cover" alt="{{ $auction->title }}">
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
                        <div class="alert alert-danger py-2 mb-3 text-center small">Auction Closed</div>
                        @endif

                        <h3 class="h5 mb-2">{{ $auction->title }}</h3>
                        <p class="text-secondary small mb-4 line-clamp-2">{{ Str::limit($auction->description, 60) }}</p>
                        <div class="d-flex justify-content-between align-items-center mb-4 pt-3 border-top border-white border-opacity-10">
                            <span class="small text-secondary">CURRENT BID</span>
                            <span class="h5 mb-0 text-gold fw-bold" style="color: var(--elite-gold);">${{ number_format($auction->current_price, 2) }}</span>
                        </div>
                        <a href="{{ route('auctions.show', $auction->id) }}" class="btn btn-gold w-100 py-2">View Details</a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <div class="opacity-50 mb-3">
                    <i class="fas fa-gavel fa-4x mb-3 text-gold"></i>
                    <h3 class="h4">No auctions found</h3>
                    <p>Try adjusting your search or category filters.</p>
                </div>
                <a href="{{ route('auctions.index') }}" class="btn btn-outline-gold mt-3">Reset Filters</a>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-5">
            {{ $auctions->links('pagination::bootstrap-5') }}
        </div>
    </div>
</section>

@endsection
