@extends('website.layouts.app')

@section('title', 'Contact Us | LaraBids')

@section('content')

<!-- Hero Header -->
<section class="hero-section text-white d-flex align-items-center">
    <div class="container" data-aos="fade-up">
        <div class="row align-items-center">
            <div class="col-lg-7 text-center text-lg-start mb-5 mb-lg-0">
                <span class="badge bg-white text-primary fw-bold px-3 py-2 rounded-pill mb-3 shadow-sm">
                    👋 WE'RE HERE TO HELP
                </span>
                <h1 class="display-3 fw-bold mb-3">Contact <span class="text-white">Us</span></h1>
                <p class="lead opacity-75 pe-lg-5">
                    Have questions or need assistance? Our team is dedicated to providing you with the best auction experience.
                </p>
                <div class="mt-4">
                    <a href="#contact-content" class="btn btn-gold btn-lg px-5 py-3 rounded-pill shadow">Get In Touch</a>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="hero-illustration-wrapper ps-lg-4 text-center">
                    <div class="hero-glow-blob"></div>
                    <img src="{{ asset('assets/images/banner-5.png') }}" 
                         class="img-fluid" 
                         alt="Contact LaraBids"
                         style="max-height: 400px; filter: drop-shadow(0 20px 50px rgba(0,0,0,0.3));">
                </div>
            </div>
        </div>
    </div>
</section>



<!-- Main Content -->
<section id="contact-content" class="py-5">

    <div class="container py-lg-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="card card-elite p-5 border-0 shadow-sm rounded-4" data-aos="fade-up">
                    <h2 class="h3 text-dark mb-4 fw-bold">Send Us a Message</h2>
                    <form action="{{ route('contact.store') }}" method="POST">
                        @csrf
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="name" class="form-label text-dark fw-bold small">Your Name</label>
                                <input type="text" class="form-control form-control-elite" id="name" name="name" 
                                    value="{{ old('name') }}" required>
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label text-dark fw-bold small">Email Address</label>
                                <input type="email" class="form-control form-control-elite" id="email" name="email" 
                                    value="{{ old('email') }}" required>
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="subject" class="form-label text-dark fw-bold small">Subject</label>
                                <input type="text" class="form-control form-control-elite" id="subject" name="subject" 
                                    value="{{ old('subject') }}" required>
                                @error('subject')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="message" class="form-label text-dark fw-bold small">Message</label>
                                <textarea class="form-control form-control-elite" id="message" name="message" 
                                    rows="6" required>{{ old('message') }}</textarea>
                                @error('message')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-gold px-5 py-3 shadow-sm">Send Message Now</button>
                            </div>
                        </div>
                    </form>
                </div>


                <!-- Contact Info -->
                <div class="row g-4 mt-4">
                    <div class="col-md-4 text-center" data-aos="fade-up" data-aos-delay="100">
                        <div class="icon-box-gold mx-auto mb-3">
                            <i class="fas fa-envelope fs-4"></i>
                        </div>
                        <h5 class="text-dark fw-bold mb-2">Email</h5>
                        <p class="text-secondary small">support@larabids.com</p>
                    </div>
                    <div class="col-md-4 text-center" data-aos="fade-up" data-aos-delay="200">
                        <div class="icon-box-gold mx-auto mb-3">
                            <i class="fas fa-phone fs-4"></i>
                        </div>
                        <h5 class="text-dark fw-bold mb-2">Phone</h5>
                        <p class="text-secondary small">+1 (555) 123-4567</p>
                    </div>
                    <div class="col-md-4 text-center" data-aos="fade-up" data-aos-delay="300">
                        <div class="icon-box-gold mx-auto mb-3">
                            <i class="fas fa-map-marker-alt fs-4"></i>
                        </div>
                        <h5 class="text-dark fw-bold mb-2">Address</h5>
                        <p class="text-secondary small">123 Auction St, NY 10001</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

@endsection
