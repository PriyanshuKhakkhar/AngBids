@extends('website.layouts.app')

@section('title', 'All Auctions | LaraBids')

@section('content')

<!-- Hero Header -->
<section class="hero-section text-center text-white d-flex align-items-center">
    <div class="container" data-aos="fade-up">
        <span class="badge bg-white text-primary fw-bold px-3 py-2 rounded-pill mb-3 shadow-sm">
            💎 EXCLUSIVE LISTINGS
        </span>
        <h1 class="display-3 fw-bold mb-3">Browse <span class="text-white">Auctions</span></h1>
        <p class="lead opacity-75 mx-auto" style="max-width: 700px;">
            Discover amazing items up for bid, from rare collectibles to high-end electronics. Professional bidding starts here.
        </p>
    </div>
</section>




<section class="py-5">
    <div class="container py-lg-5">
        
        <div class="d-flex flex-wrap gap-2 justify-content-center mb-5" data-aos="fade-up">
            
            {{-- 1. "All Auctions": Full reset. Clears Category AND Search. --}}
            <a href="{{ route('auctions.index') }}" 
               class="category-pill {{ !request('category') ? 'active' : '' }}">All Auctions</a>
            
            {{-- 2. Specific Category: Passes ONLY category. Automatically drops 'q' (search). --}}
            @foreach($categories as $category)
            <a href="{{ route('auctions.index', ['category' => $category->slug]) }}" 
               class="category-pill {{ request('category') == $category->slug ? 'active' : '' }}">
                <i class="{{ $category->icon }} me-2"></i>{{ $category->name }}
            </a>
            @endforeach

        </div>

        <div class="card card-elite p-4 mb-5 border-0 shadow-sm" data-aos="fade-up">
            <form action="{{ route('auctions.index') }}" method="GET" class="row g-3 align-items-center">
                {{-- Preserve existing filters when sorting/filtering price --}}
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                @if(request('q'))
                    <input type="hidden" name="q" value="{{ request('q') }}">
                @endif

                <div class="col-lg-5 col-md-12">
                    <div class="d-flex align-items-center gap-3">
                        <span class="text-dark small fw-bold text-nowrap">Price Range:</span>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-light text-primary fw-bold">$</span>
                            <input type="number" name="min_price" class="form-control bg-light border-light" 
                                placeholder="Min" value="{{ request('min_price') }}">
                            <input type="number" name="max_price" class="form-control bg-light border-light" 
                                placeholder="Max" value="{{ request('max_price') }}">
                        </div>
                    </div>
                </div>

                <div class="col-lg-5 col-md-12">
                    <div class="d-flex align-items-center gap-3">
                        <span class="text-dark small fw-bold text-nowrap">Sort By:</span>
                        <select name="sort" class="form-select bg-light border-light text-dark shadow-none" onchange="this.form.submit()">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Newly Listed</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="ending_soon" {{ request('sort') == 'ending_soon' ? 'selected' : '' }}>Ending Soon</option>
                        </select>
                    </div>
                </div>

                <div class="col-lg-2 col-md-12 d-grid gap-2 d-md-flex justify-content-lg-end">
                    <button type="submit" class="btn btn-gold px-4">Filter</button>
                    <a href="{{ route('auctions.index', request()->only(['category', 'q'])) }}" class="btn btn-outline-secondary px-3">
                        <i class="fas fa-undo"></i>
                    </a>
                </div>
            </form>
        </div>


        <div class="row g-4">
            @forelse($auctions as $auction)
            <div class="col-md-6 col-lg-4" data-aos="fade-up">
                <div class="card card-elite h-100">
                    <div class="position-relative overflow-hidden" style="height: 280px; border-radius: 12px 12px 0 0;">
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
                    <i class="fas fa-search fa-4x mb-3 text-primary"></i>
                    <h3 class="h4 text-dark">No auctions found</h3>
                    <p class="text-secondary">Try adjusting your search or category filters.</p>
                </div>
                <a href="{{ route('auctions.index') }}" class="btn btn-outline-primary mt-3">Reset Filters</a>
            </div>

            @endforelse
        </div>

        <div class="d-flex justify-content-center mt-5">
            {{ $auctions->links('pagination::bootstrap-5') }}
        </div>
    </div>
</section>

@endsection