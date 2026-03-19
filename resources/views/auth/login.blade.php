@extends('website.layouts.app')

@section('title', 'Login | LaraBids')

@section('content')

<div class="main-auth-container d-flex align-items-center justify-content-center py-5" style="min-height: 80vh; background-color: #ffffff;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="form-box">
                    <h2 class="text-center fw-bold mb-4">Login</h2>

                    <!-- Session Status -->
                    @if (session('status'))
                        <div class="alert alert-success border-0 small mb-4" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" id="loginForm" novalidate>
                        @csrf

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">Email Address</label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
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
                        <div class="mb-3">
                            <label for="password" class="form-label fw-bold">Password</label>
                            <div class="position-relative">
                                <input type="password" 
                                       class="form-control no-validation-icon @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       placeholder="Enter your password"
                                       style="padding-right: 45px;"
                                       required>
                                <span class="position-absolute toggle-password text-muted" 
                                      data-target="password" 
                                      style="right: 15px; top: 19px; transform: translateY(-50%); cursor: pointer; z-index: 10;">
                                    <i class="far fa-eye-slash"></i>
                                </span>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember_me" name="remember">
                                <label class="form-check-label small" for="remember_me">
                                    Remember Me
                                </label>
                            </div>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="small text-decoration-none">Forgot Password?</a>
                            @endif
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold mb-4">
                            Login
                        </button>

                        <!-- Social Login Divider -->
                        <div class="social-divider d-flex align-items-center mb-3">
                            <hr class="flex-grow-1 me-3">
                            <span class="text-muted small fw-semibold">OR</span>
                            <hr class="flex-grow-1 ms-3">
                        </div>

                        <!-- Google Login Button -->
                        @php
                            $lastEmail = request()->cookie('last_oauth_email');
                            $lastName = request()->cookie('last_oauth_name');
                            $lastAvatar = request()->cookie('last_oauth_avatar');
                        @endphp

                        @if($lastEmail)
                            <div class="mb-3 border rounded p-3 d-flex align-items-center justify-content-between" style="background: #f8f9fa;">
                                <div class="d-flex align-items-center">
                                    @if($lastAvatar)
                                        <img src="{{ $lastAvatar }}" alt="Profile" class="rounded-circle me-3 shadow-sm border" width="45" height="45" style="object-fit:cover;">
                                    @else
                                        <div class="rounded-circle me-3 d-flex align-items-center justify-content-center bg-primary text-white shadow-sm" style="width: 45px; height: 45px; font-weight: bold; font-size:18px;">
                                            {{ substr($lastName ?? 'U', 0, 1) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-bold text-dark" style="font-size: 14px;">{{ $lastName }}</div>
                                        <div class="text-muted" style="font-size: 13px;">{{ $lastEmail }}</div>
                                    </div>
                                </div>
                                <a href="{{ route('social.redirect', ['provider' => 'google', 'hint' => $lastEmail]) }}" class="btn btn-primary btn-sm px-4 py-2 fw-bold rounded-pill shadow-sm">
                                    Continue
                                </a>
                            </div>
                            <a href="{{ route('social.redirect', 'google') }}" class="btn btn-outline-secondary w-100 py-2 mb-4 d-flex align-items-center justify-content-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="18" height="18">
                                    <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                                    <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                                    <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                                    <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.18 1.48-4.97 2.31-8.16 2.31-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                                    <path fill="none" d="M0 0h48v48H0z"/>
                                </svg>
                                <span class="fw-semibold" style="font-size:14px;">Use another Google account</span>
                            </a>
                        @else
                            <a href="{{ route('social.redirect', 'google') }}" class="btn btn-google w-100 py-2 mb-4 d-flex align-items-center justify-content-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="20" height="20">
                                    <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                                    <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                                    <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                                    <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.18 1.48-4.97 2.31-8.16 2.31-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                                    <path fill="none" d="M0 0h48v48H0z"/>
                                </svg>
                                <span class="fw-semibold">Continue with Google</span>
                            </a>
                        @endif

                        <!-- Register Link -->
                        <div class="text-center mt-3">
                            <span class="text-muted small">Don't have an account? </span>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="fw-bold small text-decoration-none">Register Here</a>
                            @endif
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .form-box {
        background: #ffffff;
        padding: 40px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
    }
    .form-control:focus {
        box-shadow: none;
        border-color: #4e73df;
    }
    .btn-google {
        background: #fff;
        border: 1px solid #dadce0;
        color: #3c4043;
        font-size: 14px;
        transition: background 0.2s, box-shadow 0.2s;
    }
    .btn-google:hover {
        background: #f8f9fa;
        box-shadow: 0 1px 4px rgba(0,0,0,0.15);
        color: #3c4043;
    }
    .social-divider hr {
        border-color: #dee2e6;
        opacity: 1;
    }
    .no-validation-icon.is-invalid, 
    .was-validated .no-validation-icon:invalid,
    .no-validation-icon.is-valid, 
    .was-validated .no-validation-icon:valid {
        background-image: none !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('assets/js/form-validation.js') }}"></script>
@if (session('kicked_out'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'warning',
                title: 'Logged Out',
                text: "{{ session('kicked_out') }}",
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
        });
    </script>
@endif
<script>
    document.querySelectorAll('.toggle-password').forEach(function(button) {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        });
    });
</script>
@endpush



