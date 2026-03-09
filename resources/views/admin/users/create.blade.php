@extends('admin.layouts.admin')

@section('title', 'Create User - LaraBids')

@section('content')
    <h1 class="h3 mb-4 text-gray-800">Create New User</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">User Details</h6>
        </div>
        <div class="card-body p-4 p-md-5">
            <form action="{{ route('admin.users.store') }}" method="POST" id="userCreateForm" novalidate>
                @csrf
                
                <h5 class="font-weight-bold text-dark mb-4" style="border-left: 4px solid #4e73df; padding-left: 12px;">User Information</h5>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label for="name" class="font-weight-bold text-dark small">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control form-control-lg bg-light border-0 shadow-sm @error('name') is-invalid @enderror" id="name" value="{{ old('name') }}" placeholder="Enter full name">
                            <div class="invalid-feedback" id="name-error"></div>
                            @error('name')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label for="username" class="font-weight-bold text-dark small">Username <span class="text-danger">*</span></label>
                            <div class="input-group input-group-lg shadow-sm rounded">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-0 text-primary font-weight-bold">@</span>
                                </div>
                                <input type="text" name="username" class="form-control bg-light border-0 shadow-none @error('username') is-invalid @enderror" id="username" value="{{ old('username') }}" placeholder="username">
                                <div class="invalid-feedback" id="username-error"></div>
                            </div>
                            <small class="form-text text-muted mt-1 px-1">A-Z, 0-9, dashes and underscores only</small>
                            @error('username')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label for="email" class="font-weight-bold text-dark small">Email Address <span class="text-danger">*</span></label>
                            <div class="input-group input-group-lg shadow-sm rounded">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-0"><i class="fas fa-envelope text-primary"></i></span>
                                </div>
                                <input type="email" name="email" class="form-control bg-light border-0 shadow-none @error('email') is-invalid @enderror" id="email" value="{{ old('email') }}" placeholder="Enter email address">
                                <div class="input-group-append">
                                    <button class="btn btn-primary px-4 font-weight-bold" type="button" id="sendOtpBtn">Get OTP</button>
                                </div>
                            </div>
                            <div class="invalid-feedback d-block" id="email-error"></div>
                            @error('email')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <small class="form-text mt-1 px-1 font-weight-bold" id="otpMessage"></small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label for="otp" class="font-weight-bold text-dark small">Email OTP Verification <span class="text-muted">(Optional)</span></label>
                            <div class="input-group input-group-lg shadow-sm rounded">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-0"><i class="fas fa-key text-primary"></i></span>
                                </div>
                                <input type="text" name="otp" class="form-control bg-light border-0 shadow-none @error('otp') is-invalid @enderror" id="otp" value="{{ old('otp') }}" placeholder="6-digit OTP code">
                            </div>
                            <small class="form-text text-muted mt-1 px-1">Enter OTP to verify user immediately.</small>
                            <div class="invalid-feedback d-block" id="otp-error"></div>
                            @error('otp')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="form-group mb-4">
                            <label for="role" class="font-weight-bold text-dark small">User Role & Permissions <span class="text-danger">*</span></label>
                            <select name="role" id="role" class="form-control form-control-lg bg-light border-0 shadow-sm @error('role') is-invalid @enderror">
                                <option value="" disabled selected>Select Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>{{ ucfirst($role->name) }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="role-error"></div>
                            @error('role')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <h5 class="font-weight-bold text-dark mb-4 mt-5" style="border-left: 4px solid #1cc88a; padding-left: 12px;">Security</h5>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label for="password" class="font-weight-bold text-dark small">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control form-control-lg bg-light border-0 shadow-sm @error('password') is-invalid @enderror" id="password" placeholder="Enter secure password">
                            <small class="form-text text-muted mt-1 px-1">Minimum 8 characters</small>
                            <div class="invalid-feedback" id="password-error"></div>
                            @error('password')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label for="password_confirmation" class="font-weight-bold text-dark small">Confirm Password <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control form-control-lg bg-light border-0 shadow-sm" id="password_confirmation" placeholder="Re-enter password">
                            <div class="invalid-feedback" id="password_confirmation-error"></div>
                        </div>
                    </div>
                </div>

                <hr class="mt-5 mb-4 border-0" style="border-top: 1px dashed #e3e6f0 !important;">

                <div class="form-group mb-0 text-right mt-4">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary px-4 mr-2 rounded-pill font-weight-bold">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary px-5 rounded-pill font-weight-bold shadow">
                        <i class="fas fa-check-circle mr-1"></i> Create User
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('userCreateForm');
    const nameInput = document.getElementById('name');
    const usernameInput = document.getElementById('username');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const passwordConfirmInput = document.getElementById('password_confirmation');
    const roleSelect = document.getElementById('role');

    // Validation functions
    function validateName() {
        const value = nameInput.value.trim();
        const errorDiv = document.getElementById('name-error');
        
        if (value === '') {
            nameInput.classList.add('is-invalid');
            errorDiv.textContent = 'Full name is required.';
            return false;
        } else if (value.length < 2) {
            nameInput.classList.add('is-invalid');
            errorDiv.textContent = 'Name must be at least 2 characters.';
            return false;
        } else if (value.length > 255) {
            nameInput.classList.add('is-invalid');
            errorDiv.textContent = 'Name must not exceed 255 characters.';
            return false;
        } else {
            nameInput.classList.remove('is-invalid');
            errorDiv.textContent = '';
            return true;
        }
    }

    function validateUsername() {
        const value = usernameInput.value.trim();
        const errorDiv = document.getElementById('username-error');
        const usernameRegex = /^[a-zA-Z0-9_-]+$/;
        
        if (value === '') {
            usernameInput.classList.add('is-invalid');
            errorDiv.textContent = 'Username is required.';
            return false;
        } else if (!usernameRegex.test(value)) {
            usernameInput.classList.add('is-invalid');
            errorDiv.textContent = 'Username can only contain letters, numbers, dashes, and underscores.';
            return false;
        } else if (value.length > 255) {
            usernameInput.classList.add('is-invalid');
            errorDiv.textContent = 'Username must not exceed 255 characters.';
            return false;
        } else {
            usernameInput.classList.remove('is-invalid');
            errorDiv.textContent = '';
            return true;
        }
    }

    function validateEmail() {
        const value = emailInput.value.trim();
        const errorDiv = document.getElementById('email-error');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (value === '') {
            emailInput.classList.add('is-invalid');
            errorDiv.textContent = 'Email address is required.';
            return false;
        } else if (!emailRegex.test(value)) {
            emailInput.classList.add('is-invalid');
            errorDiv.textContent = 'Please enter a valid email address.';
            return false;
        } else if (value.length > 255) {
            emailInput.classList.add('is-invalid');
            errorDiv.textContent = 'Email must not exceed 255 characters.';
            return false;
        } else {
            emailInput.classList.remove('is-invalid');
            errorDiv.textContent = '';
            return true;
        }
    }

    function validatePassword() {
        const value = passwordInput.value;
        const errorDiv = document.getElementById('password-error');
        
        if (value === '') {
            passwordInput.classList.add('is-invalid');
            errorDiv.textContent = 'Password is required.';
            return false;
        } else if (value.length < 8) {
            passwordInput.classList.add('is-invalid');
            errorDiv.textContent = 'Password must be at least 8 characters.';
            return false;
        } else {
            passwordInput.classList.remove('is-invalid');
            errorDiv.textContent = '';
            return true;
        }
    }

    function validatePasswordConfirmation() {
        const password = passwordInput.value;
        const confirmation = passwordConfirmInput.value;
        const errorDiv = document.getElementById('password_confirmation-error');
        
        if (confirmation === '') {
            passwordConfirmInput.classList.add('is-invalid');
            errorDiv.textContent = 'Please confirm your password.';
            return false;
        } else if (password !== confirmation) {
            passwordConfirmInput.classList.add('is-invalid');
            errorDiv.textContent = 'Passwords do not match.';
            return false;
        } else {
            passwordConfirmInput.classList.remove('is-invalid');
            errorDiv.textContent = '';
            return true;
        }
    }

    function validateRole() {
        const value = roleSelect.value;
        const errorDiv = document.getElementById('role-error');
        
        if (value === '' || value === null) {
            roleSelect.classList.add('is-invalid');
            errorDiv.textContent = 'Please select a user role.';
            return false;
        } else {
            roleSelect.classList.remove('is-invalid');
            errorDiv.textContent = '';
            return true;
        }
    }

    function validateOtp() {
        const otpInput = document.getElementById('otp');
        const value = otpInput.value.trim();
        const errorDiv = document.getElementById('otp-error');
        
        if (value !== '' && !/^\d{6}$/.test(value)) {
            otpInput.classList.add('is-invalid');
            errorDiv.textContent = 'OTP must be exactly 6 digits.';
            // Add d-block in case it's outside input-group
            errorDiv.classList.add('d-block');
            return false;
        } else {
            otpInput.classList.remove('is-invalid');
            errorDiv.textContent = '';
            errorDiv.classList.remove('d-block');
            return true;
        }
    }

    // Real-time validation
    nameInput.addEventListener('blur', validateName);
    nameInput.addEventListener('input', function() {
        if (this.classList.contains('is-invalid')) {
            validateName();
        }
    });

    usernameInput.addEventListener('blur', validateUsername);
    usernameInput.addEventListener('input', function() {
        if (this.classList.contains('is-invalid')) {
            validateUsername();
        }
    });

    emailInput.addEventListener('blur', validateEmail);
    emailInput.addEventListener('input', function() {
        if (this.classList.contains('is-invalid')) {
            validateEmail();
        }
    });

    passwordInput.addEventListener('blur', validatePassword);
    passwordInput.addEventListener('input', function() {
        if (this.classList.contains('is-invalid')) {
            validatePassword();
        }
        // Also revalidate confirmation if it has been filled
        if (passwordConfirmInput.value !== '') {
            validatePasswordConfirmation();
        }
    });

    passwordConfirmInput.addEventListener('blur', validatePasswordConfirmation);
    passwordConfirmInput.addEventListener('input', function() {
        if (this.classList.contains('is-invalid') || this.value !== '') {
            validatePasswordConfirmation();
        }
    });

    roleSelect.addEventListener('change', validateRole);
    roleSelect.addEventListener('blur', validateRole);

    const otpInput = document.getElementById('otp');
    if (otpInput) {
        otpInput.addEventListener('blur', validateOtp);
        otpInput.addEventListener('input', function() {
            if (this.classList.contains('is-invalid')) {
                validateOtp();
            }
        });
    }

    // Form submission validation
    form.addEventListener('submit', function(e) {
        const isNameValid = validateName();
        const isUsernameValid = validateUsername();
        const isEmailValid = validateEmail();
        const isPasswordValid = validatePassword();
        const isPasswordConfirmValid = validatePasswordConfirmation();
        const isRoleValid = validateRole();
        const isOtpValid = validateOtp();

        if (!isNameValid || !isUsernameValid || !isEmailValid || !isPasswordValid || !isPasswordConfirmValid || !isRoleValid || !isOtpValid) {
            e.preventDefault();
            
            // Focus on first invalid field
            if (!isNameValid) {
                nameInput.focus();
            } else if (!isUsernameValid) {
                usernameInput.focus();
            } else if (!isEmailValid) {
                emailInput.focus();
            } else if (!isOtpValid) {
                otpInput.focus();
            } else if (!isRoleValid) {
                roleSelect.focus();
            } else if (!isPasswordValid) {
                passwordInput.focus();
            } else if (!isPasswordConfirmValid) {
                passwordConfirmInput.focus();
            }
        }
    });

    // Handle OTP Request
    const sendOtpBtn = document.getElementById('sendOtpBtn');
    if(sendOtpBtn) {
        sendOtpBtn.addEventListener('click', function() {
            const email = emailInput.value.trim();
            const btn = this;
            const msg = document.getElementById('otpMessage');
            
            if(!email || emailInput.classList.contains('is-invalid')) {
                msg.innerHTML = '<span class="text-danger">Please enter a valid email address first.</span>';
                emailInput.focus();
                return;
            }
            
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
            msg.innerHTML = '<span class="text-primary">Dispatching OTP...</span>';
            
            fetch('{{ route("admin.users.send_otp") }}', {
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
});
</script>
@endpush
