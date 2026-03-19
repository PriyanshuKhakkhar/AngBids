@extends('website.layouts.app')

@section('title', 'Verify OTP | LaraBids')

@section('content')

@php
    $remainingSeconds = 0;
    $email = session('forgot_password_email');
    if ($email) {
        $rateLimitKey = 'forgot-password-resend-otp:' . $email;
        $remainingSeconds = \Illuminate\Support\Facades\RateLimiter::availableIn($rateLimitKey);
    }
@endphp

<div class="main-auth-container d-flex align-items-center justify-content-center py-5" style="min-height: 80vh; background-color: #ffffff;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="form-box">
                    <h2 class="text-center fw-bold mb-2">Verify OTP</h2>
                    <p class="text-muted text-center small mb-4">
                        We've sent a 6-digit code to<br>
                        <strong class="text-dark">{{ session('forgot_password_email') }}</strong>
                    </p>

                    {{-- Success Status --}}
                    @if (session('status'))
                        <div class="alert alert-success alert-permanent border-0 small mb-4" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{-- Error --}}
                    @if ($errors->any())
                        <div class="alert alert-danger alert-permanent border-0 small mb-4" role="alert">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    {{-- OTP Form --}}
                    <form method="POST" action="{{ route('password.verify-otp') }}" id="otpForm" novalidate>
                        @csrf

                        <div class="mb-4">
                            <label for="otp" class="form-label fw-bold text-center d-block mb-3">Verification Code</label>
                            <div class="otp-input-group d-flex gap-2 justify-content-center" id="otpInputGroup">
                                <input type="text" class="otp-digit-input" maxlength="1" data-index="0" autocomplete="off" autofocus inputmode="numeric">
                                <input type="text" class="otp-digit-input" maxlength="1" data-index="1" autocomplete="off" inputmode="numeric">
                                <input type="text" class="otp-digit-input" maxlength="1" data-index="2" autocomplete="off" inputmode="numeric">
                                <input type="text" class="otp-digit-input" maxlength="1" data-index="3" autocomplete="off" inputmode="numeric">
                                <input type="text" class="otp-digit-input" maxlength="1" data-index="4" autocomplete="off" inputmode="numeric">
                                <input type="text" class="otp-digit-input" maxlength="1" data-index="5" autocomplete="off" inputmode="numeric">
                            </div>
                            {{-- Hidden input for actual form submission --}}
                            <input type="hidden" name="otp" id="otpHidden">
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold mb-3" id="verifyBtn" disabled>
                            <span class="btn-text">Verify & Continue</span>
                            <span class="btn-spinner d-none">
                                <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                Verifying...
                            </span>
                        </button>
                    </form>

                    {{-- Resend Section --}}
                    <div class="text-center mt-3">
                        <div class="d-flex align-items-center justify-content-center gap-1">
                            <span class="text-muted small">Didn't receive the code?</span>

                            <span id="countdownText" class="text-muted small fw-bold" style="display: none;">
                                <span class="otp-countdown-badge">
                                    <i class="far fa-clock me-1"></i> <span id="timer"></span>s
                                </span>
                            </span>

                            <form id="resendForm" method="POST" action="{{ route('password.resend-otp') }}" class="d-inline" style="display: none;">
                                @csrf
                                <button type="submit" id="resendBtn" class="btn btn-link p-0 m-0 align-baseline fw-bold small text-decoration-none otp-resend-link">
                                    Resend OTP
                                </button>
                            </form>
                        </div>
                    </div>

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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const digitInputs = document.querySelectorAll('.otp-digit-input');
        const hiddenOtp = document.getElementById('otpHidden');
        const verifyBtn = document.getElementById('verifyBtn');
        const otpForm = document.getElementById('otpForm');
        const formBox = document.querySelector('.form-box');

        function updateHiddenOtp() {
            let otp = '';
            digitInputs.forEach(input => {
                otp += input.value;
            });
            hiddenOtp.value = otp;
            
            const isComplete = otp.length === 6;
            verifyBtn.disabled = !isComplete;

            if (isComplete) {
                setTimeout(() => {
                    otpForm.submit();
                }, 300);
            }
        }

        digitInputs.forEach((input, index) => {
            input.addEventListener('input', function(e) {
                const val = this.value.replace(/[^0-9]/g, '');
                this.value = val ? val[val.length - 1] : '';

                if (this.value.length === 1 && index < digitInputs.length - 1) {
                    digitInputs[index + 1].focus();
                }
                updateHiddenOtp();
            });

            input.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace') {
                    if (this.value === '' && index > 0) {
                        digitInputs[index - 1].focus();
                        digitInputs[index - 1].value = '';
                    } else {
                        this.value = '';
                    }
                    updateHiddenOtp();
                } else if (e.key === 'ArrowLeft' && index > 0) {
                    digitInputs[index - 1].focus();
                } else if (e.key === 'ArrowRight' && index < digitInputs.length - 1) {
                    digitInputs[index + 1].focus();
                }
            });

            input.addEventListener('paste', function(e) {
                e.preventDefault();
                const pastedData = (e.clipboardData || window.clipboardData).getData('text').replace(/[^0-9]/g, '');
                
                for (let i = 0; i < pastedData.length && i < 6; i++) {
                    digitInputs[i].value = pastedData[i];
                }
                
                const nextIndex = Math.min(pastedData.length, 5);
                digitInputs[nextIndex].focus();
                updateHiddenOtp();
            });

            input.addEventListener('focus', function() {
                this.select();
                this.classList.add('focused');
            });
            
            input.addEventListener('blur', function() {
                this.classList.remove('focused');
            });
        });

        otpForm.addEventListener('submit', function(e) {
            if (hiddenOtp.value.length !== 6) {
                e.preventDefault();
                formBox.classList.add('otp-shake');
                setTimeout(() => formBox.classList.remove('otp-shake'), 500);
                return;
            }

            verifyBtn.querySelector('.btn-text').classList.add('d-none');
            verifyBtn.querySelector('.btn-spinner').classList.remove('d-none');
            verifyBtn.disabled = true;
        });

        const resendForm = document.getElementById('resendForm');
        const resendBtn = document.getElementById('resendBtn');
        const countdownText = document.getElementById('countdownText');
        const timerSpan = document.getElementById('timer');

        let timeLeft = {{ $remainingSeconds > 0 ? $remainingSeconds : 0 }};

        function updateTimer() {
            if (timeLeft > 0) {
                countdownText.style.display = 'inline-flex';
                resendForm.style.display = 'none';
                timerSpan.textContent = timeLeft;
                timeLeft--;
                setTimeout(updateTimer, 1000);
            } else {
                countdownText.style.display = 'none';
                resendForm.style.display = 'inline';
            }
        }

        if (timeLeft > 0) {
            updateTimer();
        } else {
            countdownText.style.display = 'none';
            resendForm.style.display = 'inline';
        }

        if (resendForm && resendBtn) {
            resendForm.addEventListener('submit', function() {
                resendBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span> Sending...';
                resendBtn.disabled = true;
                resendBtn.classList.add('text-muted');
            });
        }
    });
</script>
@endpush

@push('styles')
<style>
    .form-box {
        background: #ffffff;
        padding: 40px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
    }

    .otp-shake {
        animation: otpShake 0.5s cubic-bezier(.36,.07,.19,.97) both;
    }

    @keyframes otpShake {
        10%, 90% { transform: translate3d(-1px, 0, 0); }
        20%, 80% { transform: translate3d(2px, 0, 0); }
        30%, 50%, 70% { transform: translate3d(-4px, 0, 0); }
        40%, 60% { transform: translate3d(4px, 0, 0); }
    }

    /* OTP Digit Inputs */
    .otp-digit-input {
        width: 48px;
        height: 54px;
        text-align: center;
        font-size: 1.3rem;
        font-weight: 700;
        color: #2b3445;
        border: 1px solid #ced4da;
        border-radius: 8px;
        background: #fff;
        outline: none;
        transition: all 0.2s ease;
    }

    .otp-digit-input:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }

    .otp-countdown-badge {
        color: #e74a3b;
        font-size: 13px;
    }

    .otp-resend-link {
        color: #4e73df;
    }

    .otp-resend-link:hover {
        color: #2e59d9;
    }

    @media (max-width: 576px) {
        .otp-digit-input {
            width: 40px;
            height: 46px;
            font-size: 1.1rem;
        }
    }
</style>
@endpush



