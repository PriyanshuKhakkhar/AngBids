@extends('website.layouts.app')

@section('title', 'Confirm Password | LaraBids')

@section('content')

<!-- Auth Hero Section -->
<section class="hero-section text-white d-flex align-items-center" style="min-height: 50vh;">
    <div class="container" data-aos="fade-up">
        <div class="row align-items-center">
            <div class="col-lg-6 text-center text-lg-start mb-4 mb-lg-0">
                <span class="badge bg-white text-primary fw-bold px-3 py-2 rounded-pill mb-3 shadow-sm">
                    🔒 SECURE AREA
                </span>
                <h1 class="display-3 fw-bold mb-3">Confirm Your <span class="text-white">Password</span></h1>
                <p class="lead opacity-75">
                    This is a secure area. Please confirm your password before continuing.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Confirm Password Form Section -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="card card-elite border-0 shadow-lg p-4 p-md-5" data-aos="fade-up">
                    
                    <div class="mb-4 text-center">
                        <div class="bg-primary bg-opacity-10 p-4 rounded-circle d-inline-flex mb-3">
                            <i class="fas fa-shield-alt text-primary fs-1"></i>
                        </div>
                        <p class="text-secondary">
                            Please confirm your password to continue to this secure area.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('password.confirm') }}">
                        @csrf

                        <!-- Password -->
                        <div class="mb-4">
                            <label for="password" class="form-label fw-bold text-dark small text-uppercase">Password</label>
                            <input type="password" 
                                   class="form-control form-control-lg bg-light border-0 shadow-none @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Enter your password"
                                   required
                                   autofocus>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary btn-lg w-100 py-3 rounded-pill shadow fw-bold">
                            <i class="fas fa-check-circle me-2"></i>Confirm Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection



