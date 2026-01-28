@extends('website.layouts.app')

@section('title', 'Register | LaraBids')

@section('content')

<!-- Auth Hero Section -->
<section class="hero-section text-white d-flex align-items-center" style="min-height: 50vh;">
    <div class="container" data-aos="fade-up">
        <div class="row align-items-center">
            <div class="col-lg-6 text-center text-lg-start mb-4 mb-lg-0">
                <span class="badge bg-white text-primary fw-bold px-3 py-2 rounded-pill mb-3 shadow-sm">
                    ✨ JOIN LARABIDS
                </span>
                <h1 class="display-3 fw-bold mb-3">Create Your <span class="text-white">Account</span></h1>
                <p class="lead opacity-75">
                    Join thousands of bidders and sellers in the most exclusive online auction platform.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Register Form Section -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="card card-elite border-0 shadow-lg p-4 p-md-5" data-aos="fade-up">
                    
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- Name -->
                        <div class="mb-4">
                            <label for="name" class="form-label fw-bold text-dark small text-uppercase">Full Name</label>
                            <input type="text" 
                                   class="form-control form-control-lg bg-light border-0 shadow-none @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   placeholder="Enter your full name"
                                   required
                                   autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="form-label fw-bold text-dark small text-uppercase">Email Address</label>
                            <input type="email" 
                                   class="form-control form-control-lg bg-light border-0 shadow-none @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   placeholder="Enter your email"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <label for="password" class="form-label fw-bold text-dark small text-uppercase">Password</label>
                            <input type="password" 
                                   class="form-control form-control-lg bg-light border-0 shadow-none @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Create a strong password"
                                   required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">At least 8 characters</small>
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-bold text-dark small text-uppercase">Confirm Password</label>
                            <input type="password" 
                                   class="form-control form-control-lg bg-light border-0 shadow-none" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   placeholder="Confirm your password"
                                   required>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary btn-lg w-100 py-3 rounded-pill shadow fw-bold mb-4">
                            <i class="fas fa-user-plus me-2"></i>Create Account
                        </button>

                        <!-- Login Link -->
                        <div class="text-center">
                            <span class="text-secondary small">Already have an account? </span>
                            @if (Route::has('login'))
                                <a href="{{ route('login') }}" class="text-primary fw-bold small">Login</a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
