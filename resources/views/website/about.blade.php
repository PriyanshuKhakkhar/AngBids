@extends('website.layouts.app')

@section('title', 'About Us | LaraBids')

@section('content')

<!-- Hero Header -->
<section class="hero-section text-white d-flex align-items-center">
    <div class="container" data-aos="fade-up">
        <div class="row align-items-center">
            <div class="col-lg-7 text-center text-lg-start mb-5 mb-lg-0">
                <span class="badge bg-white text-primary fw-bold px-3 py-2 rounded-pill mb-3 shadow-sm">
                    ✨ OUR STORY & MISSION
                </span>
                <h1 class="display-3 fw-bold mb-3">About <span class="text-white">LaraBids</span></h1>
                <p class="lead opacity-75 pe-lg-5">
                    LaraBids is the premier online auction platform designed for high-value acquisitions and a seamless bidding experience for collectors worldwide.
                </p>
                <div class="mt-4">
                    <a href="#about-content" class="btn btn-gold btn-lg px-5 py-3 rounded-pill shadow">Explore Our Journey</a>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="hero-illustration-wrapper ps-lg-4 text-center">
                    <div class="hero-glow-blob"></div>
                    <img src="{{ asset('assets/images/banner-1.png') }}" 
                         class="img-fluid" 
                         alt="About LaraBids"
                         style="max-height: 400px; filter: drop-shadow(0 20px 50px rgba(0,0,0,0.3));">

                </div>
            </div>
        </div>
    </div>
</section>



<!-- About Content -->
<section id="about-content" class="py-5">

    <div class="container py-lg-5">
        <div class="row align-items-center mb-5">
            <div class="col-lg-6" data-aos="fade-right">
                <span class="badge-elite mb-3 d-inline-block">Our Story</span>
                <h2 class="display-4 fw-bold mb-4 text-dark">Building Trust in <span class="text-primary">Online Auctions</span></h2>
                <div class="pe-lg-5">
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
            </div>

            <div class="col-lg-6" data-aos="fade-left">
                <div class="card card-elite p-5 border-0 shadow-sm rounded-4">
                    <div class="row g-4 text-center">
                        <div class="col-6">
                            <h3 class="display-5 fw-bold text-primary">10K+</h3>
                            <p class="text-dark small fw-bold mb-0">Active Users</p>
                        </div>
                        <div class="col-6">
                            <h3 class="display-5 fw-bold text-primary">50K+</h3>
                            <p class="text-dark small fw-bold mb-0">Auctions Completed</p>
                        </div>
                        <div class="col-6">
                            <h3 class="display-5 fw-bold text-primary">₹10M+</h3>
                            <p class="text-dark small fw-bold mb-0">Total Value</p>
                        </div>
                        <div class="col-6">
                            <h3 class="display-5 fw-bold text-primary">99%</h3>
                            <p class="text-dark small fw-bold mb-0">Satisfaction</p>
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
                    <h4 class="h5 text-dark fw-bold mb-3">Security First</h4>
                    <p class="text-secondary small">Advanced encryption and secure payment processing protect every transaction.</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="text-center p-4">
                    <div class="icon-box-gold mx-auto mb-4">
                        <i class="fas fa-check-circle fs-2"></i>
                    </div>
                    <h4 class="h5 text-dark fw-bold mb-3">Verified Items</h4>
                    <p class="text-secondary small">Every item is thoroughly vetted and authenticated before listing.</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                <div class="text-center p-4">
                    <div class="icon-box-gold mx-auto mb-4">
                        <i class="fas fa-headset fs-2"></i>
                    </div>
                    <h4 class="h5 text-dark fw-bold mb-3">24/7 Support</h4>
                    <p class="text-secondary small">Our dedicated team is always here to help you with any questions.</p>
                </div>
            </div>
        </div>

    </div>
</section>

@endsection
