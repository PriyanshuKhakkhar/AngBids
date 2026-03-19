@extends('website.layouts.app')

@section('title', 'Reset Your Password | LaraBids')

@section('content')

<div class="main-auth-container d-flex align-items-center justify-content-center py-5" style="min-height: 80vh; background-color: #ffffff;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="form-box">
                    <h2 class="text-center fw-bold mb-4">Set New Password</h2>

                    <p class="text-muted text-center small mb-4">
                        Please create a strong password to secure your account.
                    </p>

                    {{-- Error Summary --}}
                    @if ($errors->any())
                        <div class="alert alert-danger border-0 small mb-4" role="alert">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.update-new') }}" id="resetPasswordForm" novalidate>
                        @csrf

                        <!-- Email (Readonly) -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email Address</label>
                            <input type="email" 
                                   class="form-control text-muted bg-light" 
                                   value="{{ session('forgot_password_email') }}" 
                                   readonly>
                        </div>

                        <!-- New Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label fw-bold">New Password</label>
                            <div class="position-relative">
                                <input type="password" 
                                       class="form-control no-validation-icon @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       placeholder="New password"
                                       style="padding-right: 45px;"
                                       required
                                       autofocus>
                                <span class="position-absolute toggle-password text-muted" 
                                      data-target="password" 
                                      style="right: 15px; top: 19px; transform: translateY(-50%); cursor: pointer; z-index: 10;">
                                    <i class="far fa-eye-slash"></i>
                                </span>
                            </div>
                            <div id="passwordError" class="text-danger small mt-1 d-none"></div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-bold">Confirm Password</label>
                            <div class="position-relative">
                                <input type="password" 
                                       class="form-control no-validation-icon" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       placeholder="Confirm password"
                                       style="padding-right: 45px;"
                                       required>
                                <span class="position-absolute toggle-password text-muted" 
                                      data-target="password_confirmation" 
                                      style="right: 15px; top: 19px; transform: translateY(-50%); cursor: pointer; z-index: 10;">
                                    <i class="far fa-eye-slash"></i>
                                </span>
                            </div>
                            <div id="confirmError" class="text-danger small mt-1 d-none"></div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold mb-3" id="submitBtn">
                            <span class="btn-text">Reset Password</span>
                            <span class="btn-spinner d-none">
                                <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                Updating...
                            </span>
                        </button>
                    </form>

                    {{-- Back to Login --}}
                    <div class="text-center mt-3 pt-3" style="border-top: 1px solid #f0f0f0;">
                        <a href="{{ route('login') }}" class="fw-bold small text-decoration-none">
                            Back to Login
                        </a>
                    </div>

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
    .no-validation-icon.is-invalid, 
    .was-validated .no-validation-icon:invalid,
    .no-validation-icon.is-valid, 
    .was-validated .no-validation-icon:valid {
        background-image: none !important;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const resetForm = document.getElementById('resetPasswordForm');
        const submitBtn = document.getElementById('submitBtn');
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('password_confirmation');
        
        const passwordError = document.getElementById('passwordError');
        const confirmError = document.getElementById('confirmError');

        // Toggle Password Visibility
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

        // Form Submission
        resetForm.addEventListener('submit', function(e) {
            let isValid = true;
            const password = passwordInput.value;

            // Password Length Check
            if (password.length < 8) {
                isValid = false;
                passwordInput.classList.add('is-invalid');
                passwordError.textContent = 'Password must be at least 8 characters.';
                passwordError.classList.remove('d-none');
                passwordError.classList.add('d-block');
            } 
            // Complexity Check
            else if (!password.match(/[a-z]/) || !password.match(/[A-Z]/) || !password.match(/\d/) || !password.match(/[^A-Za-z0-9]/)) {
                isValid = false;
                passwordInput.classList.add('is-invalid');
                passwordError.textContent = 'Include uppercase, lowercase, a number and a symbol.';
                passwordError.classList.remove('d-none');
                passwordError.classList.add('d-block');
            }
            else {
                passwordInput.classList.remove('is-invalid');
                passwordError.classList.add('d-none');
                passwordError.classList.remove('d-block');
            }

            // Confirm Password Check
            if (password !== confirmInput.value) {
                isValid = false;
                confirmInput.classList.add('is-invalid');
                confirmError.textContent = 'Passwords do not match.';
                confirmError.classList.remove('d-none');
                confirmError.classList.add('d-block');
            } else {
                confirmInput.classList.remove('is-invalid');
                confirmError.classList.add('d-none');
                confirmError.classList.remove('d-block');
            }

            if (!isValid) {
                e.preventDefault();
                return;
            }

            submitBtn.querySelector('.btn-text').classList.add('d-none');
            submitBtn.querySelector('.btn-spinner').classList.remove('d-none');
            submitBtn.disabled = true;
        });
    });
</script>
@endpush
