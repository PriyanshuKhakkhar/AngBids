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
        <!-- Category Filters -->
        <div class="d-flex flex-wrap gap-2 justify-content-center mb-5" data-aos="fade-up">
            <a href="{{ route('auctions.index') }}" class="category-pill active">All Auctions</a>
            <a href="{{ route('auctions.index') }}" class="category-pill"><i class="fas fa-laptop me-2"></i>Electronics</a>
            <a href="{{ route('auctions.index') }}" class="category-pill"><i class="fas fa-clock me-2"></i>Watches</a>
            <a href="{{ route('auctions.index') }}" class="category-pill"><i class="fas fa-car me-2"></i>Vintage Cars</a>
            <a href="{{ route('auctions.index') }}" class="category-pill"><i class="fas fa-gem me-2"></i>Jewelry</a>
            <a href="{{ route('auctions.index') }}" class="category-pill"><i class="fas fa-home me-2"></i>Real Estate</a>
            <a href="{{ route('auctions.index') }}" class="category-pill"><i class="fas fa-palette me-2"></i>Art</a>
        </div>

        <!-- Auction Grid -->
        <div class="row g-4">
            @for($i = 1; $i <= 9; $i++)
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $i * 100 }}">
                <div class="card card-elite h-100">
                    <div class="position-relative overflow-hidden" style="height: 280px; border-radius: 12px 12px 0 0;">
                        <img src="https://images.unsplash.com/photo-{{ 1523275335684 + $i }}?auto=format&fit=crop&w=1200"
                            class="card-img-top h-100 object-fit-cover" alt="Auction Item {{ $i }}">
                    </div>
                    <div class="card-body p-4">
                        <div class="glass-timer text-center py-2 timer-val mb-3" 
                            data-days="{{ rand(0, 5) }}" 
                            data-hours="{{ rand(0, 23) }}" 
                            data-min="{{ rand(0, 59) }}" 
                            data-sec="{{ rand(0, 59) }}">
                            <div class="row g-0 px-3">
                                <div class="col">
                                    <div class="fw-bold fs-6" data-days>00</div>
                                    <small class="opacity-50" style="font-size: 0.6rem;">DAYS</small>
                                </div>
                                <div class="col">
                                    <div class="fw-bold fs-6" data-hours>00</div>
                                    <small class="opacity-50" style="font-size: 0.6rem;">HRS</small>
                                </div>
                                <div class="col">
                                    <div class="fw-bold fs-6" data-min>00</div>
                                    <small class="opacity-50" style="font-size: 0.6rem;">MIN</small>
                                </div>
                                <div class="col">
                                    <div class="fw-bold fs-6" data-sec>00</div>
                                    <small class="opacity-50" style="font-size: 0.6rem;">SEC</small>
                                </div>
                            </div>
                        </div>
                        <h3 class="h5 mb-2">Premium Auction Item #{{ $i }}</h3>
                        <p class="text-secondary small mb-4">High-quality collectible item</p>
                        <div class="d-flex justify-content-between align-items-center mb-4 pt-3 border-top border-white border-opacity-10">
                            <span class="small text-secondary">CURRENT BID</span>
                            <span class="h5 mb-0 text-gold fw-bold" style="color: var(--elite-gold);">${{ number_format(rand(1000, 50000), 0) }}</span>
                        </div>
                        <a href="{{ route('auctions.show', $i) }}" class="btn btn-gold w-100 py-2">View Details</a>
                    </div>
                </div>
            </div>
            @endfor
        </div>

        <!-- Pagination Placeholder -->
        <div class="d-flex justify-content-center mt-5">
            <nav>
                <ul class="pagination">
                    <li class="page-item disabled"><a class="page-link bg-dark text-white border-gold" href="#">Previous</a></li>
                    <li class="page-item active"><a class="page-link bg-gold text-dark border-gold" href="#">1</a></li>
                    <li class="page-item"><a class="page-link bg-dark text-white border-gold" href="#">2</a></li>
                    <li class="page-item"><a class="page-link bg-dark text-white border-gold" href="#">3</a></li>
                    <li class="page-item"><a class="page-link bg-dark text-white border-gold" href="#">Next</a></li>
                </ul>
            </nav>
        </div>
    </div>
</section>

@endsection
