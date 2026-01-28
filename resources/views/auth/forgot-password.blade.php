@extends('website.layouts.app')

@section('title', 'Forgot Password | LaraBids')

@section('content')

<!-- Auth Hero Section -->
<section class="hero-section text-white d-flex align-items-center" style="min-height: 50vh;">
    <div class="container" data-aos="fade-up">
        <div class="row align-items-center">
            <div class="col-lg-6 text-center text-lg-start mb-4 mb-lg-0">
                <span class="badge bg-white text-primary fw-bold px-3 py-2 rounded-pill mb-3 shadow-sm">
                    🔑 PASSWORD RECOVERY
                </span>
                <h1 class="display-3 fw-bold mb-3">Forgot Your <span class="text-white">Password?</span></h1>
                <p class="lead opacity-75">
                    No problem! Enter your email and we'll send you a password reset link.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Forgot Password Form Section -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="card card-elite border-0 shadow-lg p-4 p-md-5" data-aos="fade-up">
                    
                    <!-- Session Status -->
                    @if (session('status'))
                        <div class="alert alert-success border-0 mb-4" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
                        </div>
                    @endif

                    <div class="mb-4 text-center">
                        <div class="bg-primary bg-opacity-10 p-4 rounded-circle d-inline-flex mb-3">
                            <i class="fas fa-lock text-primary fs-1"></i>
                        </div>
                        <p class="text-secondary">
                            Enter your email address and we'll send you instructions to reset your password.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="form-label fw-bold text-dark small text-uppercase">Email Address</label>
                            <input type="email" 
                                   class="form-control form-control-lg bg-light border-0 shadow-none @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   placeholder="Enter your email"
                                   required
                                   autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary btn-lg w-100 py-3 rounded-pill shadow fw-bold mb-4">
                            <i class="fas fa-paper-plane me-2"></i>Send Reset Link
                        </button>

                        <!-- Back to Login -->
                        <div class="text-center">
                            <a href="{{ route('login') }}" class="text-primary fw-bold small">
                                <i class="fas fa-arrow-left me-2"></i>Back to Login
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
