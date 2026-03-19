@extends('website.layouts.app')

@section('title', 'Register | LaraBids')

@section('content')

<div class="main-auth-container d-flex align-items-center justify-content-center py-5" style="min-height: 80vh; background-color: #ffffff;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="form-box">
                    <h2 class="text-center fw-bold mb-4">Create Account</h2>

                    <form method="POST" action="{{ route('register') }}" id="registerForm" novalidate>
                        @csrf

                        <div class="row">
                            <!-- Name -->
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label fw-bold">Full Name</label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       placeholder="Full Name"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Username -->
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label fw-bold">Username</label>
                                <input type="text"
                                          class="form-control @error('username') is-invalid @enderror" 
                                          id="username" 
                                          name="username" 
                                          value="{{ old('username') }}" 
                                          placeholder="Username"
                                          required>
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label fw-bold">Email Address</label>
                                <div class="input-group">
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           placeholder="Enter your email"
                                           required>
                                    <button class="btn btn-outline-primary fw-bold" type="button" id="sendOtpBtn">Get OTP</button>
                                </div>
                                @error('email')
                                    <div class="invalid-feedback" data-server-error="true">{{ $message }}</div>
                                @enderror
                                <small class="form-text mt-1 fw-bold" id="otpMessage"></small>
                            </div>

                            <!-- OTP -->
                            <div class="col-md-6 mb-3">
                                <label for="otp" class="form-label fw-bold">Email Verification OTP</label>
                                <input type="text" 
                                       class="form-control @error('otp') is-invalid @enderror" 
                                       id="otp" 
                                       name="otp" 
                                       value="{{ old('otp') }}" 
                                       placeholder="6-digit code"
                                       maxlength="6"
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                       required>
                                @error('otp')
                                    <div class="invalid-feedback" data-server-error="true">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- Password -->
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label fw-bold">Password</label>
                                <div class="position-relative">
                                    <input type="password" 
                                           class="form-control no-validation-icon @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password" 
                                           placeholder="Password"
                                           style="padding-right: 45px;"
                                           required>
                                    <span class="position-absolute toggle-password text-muted" 
                                          data-target="password" 
                                          style="right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; z-index: 10;">
                                        <i class="far fa-eye-slash"></i>
                                    </span>
                                </div>
                                <div class="invalid-feedback" id="password-error" style="display: none;"></div>
                                @error('password')
                                    <div class="invalid-feedback d-block" data-server-error="true">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label fw-bold">Confirm Password</label>
                                <div class="position-relative">
                                    <input type="password" 
                                           class="form-control no-validation-icon" 
                                           id="password_confirmation" 
                                           name="password_confirmation" 
                                           placeholder="Confirm"
                                           style="padding-right: 45px;"
                                           required>
                                    <span class="position-absolute toggle-password text-muted" 
                                          data-target="password_confirmation" 
                                          style="right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; z-index: 10;">
                                        <i class="far fa-eye-slash"></i>
                                    </span>
                                </div>
                                <div class="invalid-feedback" id="password_confirmation-error" style="display: none;"></div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold mt-3 mb-4">
                            Register
                        </button>

                        <!-- Login Link -->
                        <div class="text-center">
                            <span class="text-muted small">Already have an account? </span>
                            @if (Route::has('login'))
                                <a href="{{ route('login') }}" class="fw-bold small text-decoration-none">Login Here</a>
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
    .no-validation-icon.is-invalid, 
    .was-validated .no-validation-icon:invalid,
    .no-validation-icon.is-valid, 
    .was-validated .no-validation-icon:valid {
        background-image: none !important;
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('assets/js/form-validation.js') }}"></script>
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

    const sendOtpBtn = document.getElementById('sendOtpBtn');
    const emailInput = document.getElementById('email');
    if(sendOtpBtn) {
        sendOtpBtn.addEventListener('click', function() {
            const email = emailInput.value.trim();
            const btn = this;
            const msg = document.getElementById('otpMessage');
            
            if(!email) {
                msg.innerHTML = '<span class="text-danger">Please enter a valid email address first.</span>';
                emailInput.focus();
                return;
            }
            
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
            msg.innerHTML = '<span class="text-primary">Dispatching OTP...</span>';
            
            fetch('{{ route("register.send_otp") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ email: email })
            })
            .then(response => {
                if(response.status === 422) {
                    return response.json().then(data => { throw new Error(data.message || 'Validation Error'); });
                }
                if(!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if(data.success) {
                    msg.innerHTML = '<span class="text-success"><i class="fas fa-check-circle"></i> ' + data.message + '</span>';
                    
                    let timeleft = 60;
                    let downloadTimer = setInterval(function(){
                      if(timeleft <= 0){
                        clearInterval(downloadTimer);
                        btn.innerHTML = "Resend OTP";
                        btn.disabled = false;
                      } else {
                        btn.innerHTML = "Wait " + timeleft + "s";
                      }
                      timeleft -= 1;
                    }, 1000);
                } else {
                    msg.innerHTML = '<span class="text-danger">' + (data.message || 'Error sending OTP') + '</span>';
                    btn.disabled = false;
                    btn.innerHTML = 'Get OTP';
                }
            })
            .catch(error => {
                msg.innerHTML = '<span class="text-danger"><i class="fas fa-exclamation-triangle"></i> ' + error.message + '</span>';
                btn.disabled = false;
                btn.innerHTML = 'Get OTP';
            });
        });
    }

    if(emailInput) {
        emailInput.addEventListener('input', function() {
            const msg = document.getElementById('otpMessage');
            // Clear the "Please enter a valid email address first" message when user starts typing
            if (msg && msg.innerHTML.includes('text-danger')) {
                if (msg.innerHTML.includes('first') || msg.innerHTML.includes('valid')) {
                    msg.innerHTML = '';
                }
            }
        });
    }
</script>
@endpush



