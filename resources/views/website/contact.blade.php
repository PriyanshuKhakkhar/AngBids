@extends('website.layouts.app')

@section('title', 'Contact Us | LaraBids')

@section('content')

<!-- Breadcrumb -->
<section class="breadcrumb-elite text-center text-white py-5">
    <div class="container">
        <h1 class="display-3 fw-bold mb-3">Contact <span class="gold-text">Us</span></h1>
        <p class="lead opacity-75">We'd Love to Hear From You</p>
    </div>
</section>

<!-- Contact Form -->
<section class="py-5">
    <div class="container py-lg-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="glass-panel p-5" data-aos="fade-up">
                    <h2 class="h3 text-white mb-4">Send Us a Message</h2>
                    <form action="{{ route('contact.store') }}" method="POST">
                        @csrf
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="name" class="form-label text-white">Your Name</label>
                                <input type="text" class="form-control form-control-elite" id="name" name="name" 
                                    value="{{ old('name') }}" required>
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label text-white">Email Address</label>
                                <input type="email" class="form-control form-control-elite" id="email" name="email" 
                                    value="{{ old('email') }}" required>
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="subject" class="form-label text-white">Subject</label>
                                <input type="text" class="form-control form-control-elite" id="subject" name="subject" 
                                    value="{{ old('subject') }}" required>
                                @error('subject')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="message" class="form-label text-white">Message</label>
                                <textarea class="form-control form-control-elite" id="message" name="message" 
                                    rows="6" required>{{ old('message') }}</textarea>
                                @error('message')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-gold px-5 py-3">Send Message</button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Contact Info -->
                <div class="row g-4 mt-4">
                    <div class="col-md-4 text-center" data-aos="fade-up" data-aos-delay="100">
                        <div class="icon-box-gold mx-auto mb-3">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <h5 class="text-white mb-2">Email</h5>
                        <p class="text-secondary small">support@larabids.com</p>
                    </div>
                    <div class="col-md-4 text-center" data-aos="fade-up" data-aos-delay="200">
                        <div class="icon-box-gold mx-auto mb-3">
                            <i class="fas fa-phone"></i>
                        </div>
                        <h5 class="text-white mb-2">Phone</h5>
                        <p class="text-secondary small">+1 (555) 123-4567</p>
                    </div>
                    <div class="col-md-4 text-center" data-aos="fade-up" data-aos-delay="300">
                        <div class="icon-box-gold mx-auto mb-3">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <h5 class="text-white mb-2">Address</h5>
                        <p class="text-secondary small">123 Auction St, NY 10001</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
