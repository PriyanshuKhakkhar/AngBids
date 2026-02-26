@extends('website.layouts.app')

@section('title', 'Reset Your Password | LaraBids')

@section('content')

<div class="auth-section d-flex align-items-center justify-content-center py-5" style="min-height: 80vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="auth-card">

                    {{-- Icon & Header --}}
                    <div class="text-center mb-4">
                        <div class="auth-icon-wrapper mx-auto mb-3">
                            <div class="auth-icon-pulse"></div>
                            <div class="auth-icon-circle d-flex align-items-center justify-content-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2m3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2M5 8h6a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1"/>
                                </svg>
                            </div>
                        </div>
                        <h2 class="fw-bold fs-4 text-dark mb-2">Set New Password</h2>
                        <p class="text-muted small px-3">
                            Please create a strong password to secure your account.
                        </p>
                    </div>

                    {{-- Error Summary --}}
                    @if ($errors->any())
                        <div class="auth-alert auth-alert-danger mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="me-2 flex-shrink-0" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
                            </svg>
                            <span>{{ $errors->first() }}</span>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.update-new') }}" id="resetPasswordForm" novalidate>
                        @csrf

                        <!-- Email (Readonly) -->
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase mb-1" style="letter-spacing: 0.5px;">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="text-muted" viewBox="0 0 16 16">
                                        <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 2.383-4.708 2.825L15 11.105zm0 6.502L8.696 8.16l-1.392.835-1.392-.835L1 11.885V11.89l6.304-3.783L8 8.414l.696-.418L15 11.885zM1 11.105l4.708-2.897L1 5.383z"/>
                                    </svg>
                                </span>
                                <input type="email" 
                                       class="form-control bg-light border-start-0 ps-0 text-muted" 
                                       value="{{ session('forgot_password_email') }}" 
                                       readonly>
                            </div>
                        </div>

                        <!-- New Password -->
                        <div class="mb-3 position-relative">
                            <label for="password" class="form-label fw-bold small text-muted text-uppercase mb-1" style="letter-spacing: 0.5px;">New Password</label>
                            <div class="input-group">
                                <span class="input-group-text border-end-0 bg-white" id="password_addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="text-muted" viewBox="0 0 16 16">
                                        <path d="M3.5 11.5a3.5 3.5 0 1 1 3.163-5H14L15.5 8 14 9.5l-1-1-1 1-1-1-1 1-1-1-1 1H6.663a3.5 3.5 0 0 1-3.163 2M2.5 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2"/>
                                    </svg>
                                </span>
                                <input type="password" 
                                       class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       placeholder="••••••••"
                                       required
                                       autofocus>
                                <button class="btn btn-outline-light border text-muted px-3" type="button" id="togglePassword">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.827 8q-.008.131-.021.318-.042.502-.14 1.043-.45 2.508-2.618 3.541A13 13 0 0 1 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13 13 0 0 1 1.173 8"/>
                                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
                                    </svg>
                                </button>
                                <div id="passwordError" class="invalid-feedback d-none"></div>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="password-strength-mt mt-2 d-none" id="strengthMeter">
                                <div class="progress" style="height: 4px;">
                                    <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                                </div>
                                <span class="small text-muted strength-text mt-1 d-block"></span>
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-bold small text-muted text-uppercase mb-1" style="letter-spacing: 0.5px;">Confirm Password</label>
                            <div class="input-group">
                                <span class="input-group-text border-end-0 bg-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="text-muted" viewBox="0 0 16 16">
                                        <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z"/>
                                    </svg>
                                </span>
                                <input type="password" 
                                       class="form-control border-start-0 ps-0" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       placeholder="••••••••"
                                       required>
                                <div id="confirmError" class="invalid-feedback d-none"></div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-auth-primary w-100 py-3 fw-bold mb-3 fs-6" id="submitBtn">
                            <span class="btn-text">Reset Password</span>
                            <span class="btn-spinner d-none">
                                <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                Updating...
                            </span>
                        </button>
                    </form>

                    {{-- Back to Login --}}
                    <div class="text-center mt-3 pt-3" style="border-top: 1px solid #f0f0f0;">
                        <a href="{{ route('login') }}" class="auth-back-link small text-decoration-none d-inline-flex align-items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
                            </svg>
                            <span>Back to Login</span>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const resetForm = document.getElementById('resetPasswordForm');
        const submitBtn = document.getElementById('submitBtn');
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('password_confirmation');
        const toggleBtn = document.getElementById('togglePassword');
        const strengthMeter = document.getElementById('strengthMeter');
        const progressBar = strengthMeter.querySelector('.progress-bar');
        const strengthText = strengthMeter.querySelector('.strength-text');
        
        const passwordError = document.getElementById('passwordError');
        const confirmError = document.getElementById('confirmError');

        // Toggle Password Visibility
        toggleBtn.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            confirmInput.setAttribute('type', type);
            
            // Toggle Icon
            this.innerHTML = type === 'password' ? 
                `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.827 8q-.008.131-.021.318-.042.502-.14 1.043-.45 2.508-2.618 3.541A13 13 0 0 1 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13 13 0 0 1 1.173 8"/>
                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
                </svg>` : 
                `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-slash" viewBox="0 0 16 16">
                    <path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7 7 0 0 0-2.79.588l.77.771A6 6 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.008.131-.021.318a4 4 0 0 1-.674 1.398zm2.708 3.413-1.047-1.047-1.04-1.04-2.29-2.29-1.304-1.304-1.304-1.304-1.812-1.812-1.047-1.047-1.047-1.047L1.171 2.343a.5.5 0 0 1 .708-.706l1.047 1.047 1.047 1.047 1.047 1.047 1.812 1.812 1.304 1.304 1.304 1.304 2.29 2.29 1.04 1.04 1.047 1.047a.5.5 0 1 1-.708.708l-1.047-1.047-1.047-1.047-1.04-1.04-2.29-2.29-1.304-1.304-1.304-1.304-1.304-1.304-1.047-1.047-.708.708L3.99 15.606a.5.5 0 0 1-.708-.708l.746-.746 1.047-1.047.708-.708a.5.5 0 0 1 .708.708l-.745.746 1.047 1.047.708-.708l.746-.746a.5.5 0 0 1 .708.708l-1.047 1.047-.708.708a.5.5 0 0 1 .708.708l.745-.746 1.047 1.047-.708.708z"/>
                    <path d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829zm4.95 2.829-1.578-1.578a2.5 2.5 0 0 0-1.403-1.403L5.871 6.045a2.5 2.5 0 0 0 3.518 3.518z"/>
                </svg>`;
        });

        // Strength Checker
        passwordInput.addEventListener('input', function() {
            const val = this.value;
            strengthMeter.classList.remove('d-none');
            
            let strength = 0;
            if (val.length >= 8) strength += 25;
            if (val.match(/[a-z]/) && val.match(/[A-Z]/)) strength += 25;
            if (val.match(/\d/)) strength += 25;
            if (val.match(/[^a-zA-Z\d]/)) strength += 25;

            progressBar.style.width = strength + '%';
            
            if (strength <= 25) {
                progressBar.className = 'progress-bar bg-danger';
                strengthText.textContent = 'Weak password';
            } else if (strength <= 50) {
                progressBar.className = 'progress-bar bg-warning';
                strengthText.textContent = 'Moderate password';
            } else if (strength <= 75) {
                progressBar.className = 'progress-bar bg-info';
                strengthText.textContent = 'Strong password';
            } else {
                progressBar.className = 'progress-bar bg-success';
                strengthText.textContent = 'Very strong password';
            }

            // Real-time clearance of manual error
            if (val.length >= 8 && val.match(/[a-z]/) && val.match(/[A-Z]/) && val.match(/\d/)) {
                 passwordInput.classList.remove('is-invalid');
                 passwordError.classList.add('d-none');
            }
        });

        confirmInput.addEventListener('input', function() {
            if (this.value === passwordInput.value) {
                confirmInput.classList.remove('is-invalid');
                confirmError.classList.add('d-none');
            }
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
            else if (!password.match(/[a-z]/) || !password.match(/[A-Z]/) || !password.match(/\d/)) {
                isValid = false;
                passwordInput.classList.add('is-invalid');
                passwordError.textContent = 'Include uppercase, lowercase, and a number.';
                passwordError.classList.remove('d-none');
                passwordError.classList.add('d-block');
            }
            else {
                passwordInput.classList.remove('is-invalid');
                passwordError.classList.add('d-none');
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

@push('styles')
<style>
    .auth-section {
        background: linear-gradient(135deg, #f8f9ff 0%, #f0f4ff 50%, #fef8f8 100%);
    }

    .auth-card {
        background: #ffffff;
        border: 1px solid #e8ecf4;
        border-radius: 16px;
        padding: 36px 32px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06), 0 1px 4px rgba(0, 0, 0, 0.03);
    }

    .auth-icon-wrapper {
        position: relative;
        width: 72px;
        height: 72px;
    }

    .auth-icon-circle {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        color: #ffffff;
        position: relative;
        z-index: 2;
        box-shadow: 0 4px 14px rgba(78, 115, 223, 0.35);
    }

    .auth-icon-pulse {
        position: absolute;
        top: 0; left: 0;
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: rgba(78, 115, 223, 0.2);
        z-index: 1;
        animation: authPulse 2s ease-in-out infinite;
    }

    @keyframes authPulse {
        0%, 100% { transform: scale(1); opacity: 0.6; }
        50% { transform: scale(1.25); opacity: 0; }
    }

    .form-control {
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 0.95rem;
        border: 1px solid #e2e8f0;
    }

    .form-control:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.1);
    }

    .input-group-text {
        border-radius: 10px 0 0 10px;
        border: 1px solid #e2e8f0;
    }

    .btn-auth-primary {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        color: #ffffff;
        border: none;
        border-radius: 12px;
        font-size: 15px;
        letter-spacing: 0.3px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 14px rgba(78, 115, 223, 0.3);
    }

    .btn-auth-primary:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(78, 115, 223, 0.45);
        color: #ffffff;
    }

    .auth-alert {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        border-radius: 10px;
        font-size: 13px;
    }

    .auth-alert-danger {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }

    .auth-back-link {
        color: #94a3b8;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .auth-back-link:hover {
        color: #4e73df;
    }

    @media (max-width: 576px) {
        .auth-card {
            padding: 28px 20px;
        }
    }
</style>
@endpush
