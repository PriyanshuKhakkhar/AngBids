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

<div class="otp-verify-section d-flex align-items-center justify-content-center py-5" style="min-height: 80vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="otp-card">

                    {{-- Icon & Header --}}
                    <div class="text-center mb-4">
                        <div class="otp-icon-wrapper mx-auto mb-3">
                            <div class="otp-icon-pulse"></div>
                            <div class="otp-icon-circle d-flex align-items-center justify-content-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M2 2a2 2 0 0 0-2 2v8.01A2 2 0 0 0 2 14h5.5a.5.5 0 0 0 0-1H2a1 1 0 0 1-1-1V4.01A1 1 0 0 1 2 3h12a1 1 0 0 1 1 1v4.5a.5.5 0 0 0 1 0V4a2 2 0 0 0-2-2z"/>
                                    <path d="M16 12.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0m-1.993-1.679a.5.5 0 0 0-.686.172l-1.17 1.95-.547-.547a.5.5 0 0 0-.708.708l.774.773a.75.75 0 0 0 1.174-.144l1.335-2.226a.5.5 0 0 0-.172-.686"/>
                                </svg>
                            </div>
                        </div>
                        <h2 class="fw-bold fs-4 text-dark mb-2">Reset Password</h2>
                        <p class="text-muted small px-2 mb-0">
                            We've sent a 6-digit code to<br>
                            <strong class="text-dark">{{ session('forgot_password_email') }}</strong>
                        </p>
                    </div>

                    {{-- Success Status --}}
                    @if (session('status'))
                        <div class="otp-alert otp-alert-success mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="me-2 flex-shrink-0" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                            </svg>
                            <span>{{ session('status') }}</span>
                        </div>
                    @endif

                    {{-- Error --}}
                    @if ($errors->any())
                        <div class="otp-alert otp-alert-danger mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="me-2 flex-shrink-0" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
                            </svg>
                            <span>{{ $errors->first() }}</span>
                        </div>
                    @endif

                    {{-- OTP Form --}}
                    <form method="POST" action="{{ route('password.verify-otp') }}" id="otpForm" novalidate>
                        @csrf

                        <div class="mb-4">
                            <label for="otp" class="form-label fw-semibold small text-muted text-uppercase mb-2" style="letter-spacing: 0.5px;">Verification Code</label>
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

                        <button type="submit" class="btn btn-otp-verify w-100 py-3 fw-bold mb-3 fs-6" id="verifyBtn" disabled>
                            <span class="btn-text">Verify & Continue</span>
                            <span class="btn-spinner d-none">
                                <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                Verifying...
                            </span>
                        </button>
                    </form>

                    {{-- Resend Section --}}
                    <div class="text-center mt-2">
                        <div class="d-flex align-items-center justify-content-center gap-1">
                            <span class="text-muted small">Didn't receive the code?</span>

                            <span id="countdownText" class="text-muted small fw-bold" style="display: none;">
                                <span class="otp-countdown-badge">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="me-1" viewBox="0 0 16 16">
                                        <path d="M8 3.5a.5.5 0 0 0-1 0V8a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 7.71z"/>
                                        <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0"/>
                                    </svg>
                                    <span id="timer"></span>s
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
                    <div class="text-center mt-4 pt-3" style="border-top: 1px solid #f0f0f0;">
                        <a href="{{ route('login') }}" class="otp-back-link small text-decoration-none d-inline-flex align-items-center gap-1">
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
        const digitInputs = document.querySelectorAll('.otp-digit-input');
        const hiddenOtp = document.getElementById('otpHidden');
        const verifyBtn = document.getElementById('verifyBtn');
        const otpForm = document.getElementById('otpForm');
        const otpCard = document.querySelector('.otp-card');

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
                this.parentElement.classList.add('focused-group');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('focused-group');
            });
        });

        otpForm.addEventListener('submit', function(e) {
            if (hiddenOtp.value.length !== 6) {
                e.preventDefault();
                otpCard.classList.add('otp-shake');
                setTimeout(() => otpCard.classList.remove('otp-shake'), 500);
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
    .otp-verify-section {
        background: linear-gradient(135deg, #f8f9fc 0%, #f0f4ff 50%, #fef8f8 100%);
    }

    .otp-card {
        background: #ffffff;
        border: 1px solid #e8ecf4;
        border-radius: 16px;
        padding: 36px 32px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06), 0 1px 4px rgba(0, 0, 0, 0.03);
    }

    /* Icon with pulse animation */
    .otp-icon-wrapper {
        position: relative;
        width: 72px;
        height: 72px;
    }

    .otp-icon-circle {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4e73df 0%, #2e59d9 100%);
        color: #ffffff;
        position: relative;
        z-index: 2;
        box-shadow: 0 4px 14px rgba(78, 115, 223, 0.35);
    }

    .otp-icon-pulse {
        position: absolute;
        top: 0; left: 0;
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: rgba(78, 115, 223, 0.2);
        z-index: 1;
        animation: otpPulse 2s ease-in-out infinite;
    }

    @keyframes otpPulse {
        0%, 100% { transform: scale(1); opacity: 0.6; }
        50% { transform: scale(1.25); opacity: 0; }
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
        width: 50px;
        height: 56px;
        text-align: center;
        font-size: 1.4rem;
        font-weight: 700;
        color: #2b3445;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        background: #f8fafc;
        outline: none;
        transition: all 0.2s ease;
        caret-color: #4e73df;
    }

    .otp-digit-input:focus {
        border-color: #4e73df;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.15);
        transform: translateY(-2px);
    }

    .otp-digit-input:not(:placeholder-shown) {
        border-color: #4e73df;
        background: #ffffff;
    }

    .focused-group .otp-digit-input:not(:focus) {
        border-color: #cbd5e1;
    }

    /* Verify Button */
    .btn-otp-verify {
        background: linear-gradient(135deg, #4e73df 0%, #2e59d9 100%);
        color: #ffffff;
        border: none;
        border-radius: 12px;
        font-size: 15px;
        letter-spacing: 0.3px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 14px rgba(78, 115, 223, 0.3);
    }

    .btn-otp-verify:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(78, 115, 223, 0.45);
        color: #ffffff;
    }

    .btn-otp-verify:disabled {
        opacity: 0.55;
        color: #ffffff;
        cursor: not-allowed;
    }

    /* Alert Styles */
    .otp-alert {
        display: flex;
        align-items: center;
        padding: 10px 14px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 500;
    }

    .otp-alert-success {
        background: #ecfdf5;
        color: #047857;
        border: 1px solid #a7f3d0;
    }

    .otp-alert-danger {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }

    /* Countdown Badge */
    .otp-countdown-badge {
        display: inline-flex;
        align-items: center;
        background: #f1f5f9;
        color: #64748b;
        padding: 2px 10px;
        border-radius: 20px;
        font-size: 12px;
    }

    /* Resend Link */
    .otp-resend-link {
        color: #4e73df;
        font-weight: 600;
        transition: color 0.2s ease;
    }

    .otp-resend-link:hover {
        color: #2e59d9;
    }

    /* Back Link */
    .otp-back-link {
        color: #94a3b8;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .otp-back-link:hover {
        color: #4e73df;
    }

    /* Responsive */
    @media (max-width: 576px) {
        .otp-card {
            padding: 28px 20px;
            margin: 0 8px;
        }

        .otp-digit-input {
            width: 44px;
            height: 50px;
            font-size: 1.2rem;
        }
    }
</style>
@endpush



