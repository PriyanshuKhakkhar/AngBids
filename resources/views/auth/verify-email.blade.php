@extends('website.layouts.app')

@section('title', 'Verify Email | LaraBids')

@section('content')

<!-- Auth Hero Section -->
<section class="hero-section text-white d-flex align-items-center" style="min-height: 50vh;">
    <div class="container" data-aos="fade-up">
        <div class="row align-items-center">
            <div class="col-lg-6 text-center text-lg-start mb-4 mb-lg-0">
                <span class="badge bg-white text-primary fw-bold px-3 py-2 rounded-pill mb-3 shadow-sm">
                    ✉️ EMAIL VERIFICATION
                </span>
                <h1 class="display-3 fw-bold mb-3">Verify Your <span class="text-white">Email</span></h1>
                <p class="lead opacity-75">
                    We've sent you a verification link to secure your account.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Verify Email Section -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="card card-elite border-0 shadow-lg p-4 p-md-5 text-center" data-aos="fade-up">
                    
                    <div class="bg-primary bg-opacity-10 p-4 rounded-circle d-inline-flex mx-auto mb-4">
                        <i class="fas fa-envelope-open-text text-primary fs-1"></i>
                    </div>

                    <h4 class="fw-bold text-dark mb-3">Check Your Email</h4>
                    
                    <p class="text-secondary mb-4">
                        Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.
                    </p>

                    @if (session('status') == 'verification-link-sent')
                        <div class="alert alert-success border-0 mb-4" role="alert">
                            <i class="fas fa-check-circle me-2"></i>A new verification link has been sent to your email address.
                        </div>
                    @endif

                    <div class="d-grid gap-3">
                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-lg w-100 py-3 rounded-pill shadow fw-bold">
                                <i class="fas fa-paper-plane me-2"></i>Resend Verification Email
                            </button>
                        </form>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary btn-lg w-100 py-3 rounded-pill fw-bold">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection



