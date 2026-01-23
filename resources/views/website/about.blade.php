@extends('website.layouts.app')

@section('title', 'About Us | LaraBids')

@section('content')

<!-- Breadcrumb -->
<section class="breadcrumb-elite text-center text-white py-5">
    <div class="container">
        <h1 class="display-3 fw-bold mb-3">About <span class="gold-text">LaraBids</span></h1>
        <p class="lead opacity-75">The Premier Online Auction Platform</p>
    </div>
</section>

<!-- About Content -->
<section class="py-5">
    <div class="container py-lg-5">
        <div class="row align-items-center mb-5">
            <div class="col-lg-6" data-aos="fade-right">
                <span class="badge-elite mb-3 d-inline-block">Our Story</span>
                <h2 class="display-4 fw-bold mb-4">Building Trust in <span class="gold-text">Online Auctions</span></h2>
                <p class="text-secondary mb-4">
                    LaraBids was founded with a simple mission: to create a transparent, secure, and user-friendly platform 
                    for online auctions. We believe that everyone should have access to high-quality items through fair 
                    and competitive bidding.
                </p>
                <p class="text-secondary mb-4">
                    Our platform combines cutting-edge technology with traditional auction house values, ensuring that 
                    every transaction is secure, every item is authentic, and every bidder has an equal opportunity to win.
                </p>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <div class="glass-panel p-5">
                    <div class="row g-4 text-center">
                        <div class="col-6">
                            <h3 class="display-4 fw-bold gold-text">10K+</h3>
                            <p class="text-secondary small">Active Users</p>
                        </div>
                        <div class="col-6">
                            <h3 class="display-4 fw-bold gold-text">50K+</h3>
                            <p class="text-secondary small">Auctions Completed</p>
                        </div>
                        <div class="col-6">
                            <h3 class="display-4 fw-bold gold-text">$10M+</h3>
                            <p class="text-secondary small">Total Value Traded</p>
                        </div>
                        <div class="col-6">
                            <h3 class="display-4 fw-bold gold-text">99%</h3>
                            <p class="text-secondary small">Customer Satisfaction</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Values -->
        <div class="row g-4 mt-5">
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="text-center p-4">
                    <div class="icon-box-gold mx-auto mb-4">
                        <i class="fas fa-shield-alt fs-2"></i>
                    </div>
                    <h4 class="h5 text-white mb-3">Security First</h4>
                    <p class="text-secondary small">Advanced encryption and secure payment processing protect every transaction.</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="text-center p-4">
                    <div class="icon-box-gold mx-auto mb-4">
                        <i class="fas fa-check-circle fs-2"></i>
                    </div>
                    <h4 class="h5 text-white mb-3">Verified Items</h4>
                    <p class="text-secondary small">Every item is thoroughly vetted and authenticated before listing.</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                <div class="text-center p-4">
                    <div class="icon-box-gold mx-auto mb-4">
                        <i class="fas fa-headset fs-2"></i>
                    </div>
                    <h4 class="h5 text-white mb-3">24/7 Support</h4>
                    <p class="text-secondary small">Our dedicated team is always here to help you with any questions.</p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
