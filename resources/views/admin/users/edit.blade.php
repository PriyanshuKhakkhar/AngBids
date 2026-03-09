@extends('admin.layouts.admin')

@section('title', 'Edit User - LaraBids')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit User: {{ $user->name }}</h1>
        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50 mr-1"></i> Back to List
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Update User Details</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST" id="userEditForm" novalidate>
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" value="{{ old('name', $user->name) }}">
                            <div class="invalid-feedback" id="name-error"></div>
                            @error('name')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="username">Username <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">@</span>
                                </div>
                                <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" id="username" value="{{ old('username', $user->username) }}">
                            </div>
                            <small class="form-text text-muted">A-Z, 0-9, dashes and underscores only</small>
                            <div class="invalid-feedback" id="username-error"></div>
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
                        <div class="form-group">
                            <label for="email">Email Address <span class="text-muted small">(Cannot be changed)</span></label>
                            <input type="email" class="form-control" value="{{ $user->email }}" disabled style="cursor: not-allowed; background-color: #eaecf4;">
                            <input type="hidden" name="email" id="email" value="{{ $user->email }}">
                            <div class="invalid-feedback" id="email-error"></div>
                            @error('email')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="role">User Role <span class="text-danger">*</span></label>
                            <select name="role" id="role" class="form-control @error('role') is-invalid @enderror" {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                <option value="" disabled>Select Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ (old('role') ? old('role') == $role->name : $user->hasRole($role->name)) ? 'selected' : '' }}>{{ ucfirst($role->name) }}</option>
                                @endforeach
                            </select>
                            @if($user->id === auth()->id())
                                <input type="hidden" name="role" value="{{ $user->roles->first()->name }}">
                                <small class="text-muted mt-1 d-block">You cannot change your own role.</small>
                            @endif
                            <div class="invalid-feedback" id="role-error"></div>
                            @error('role')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <hr>

                <div class="form-group mb-0">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save fa-sm text-white-50 mr-1"></i> Update User
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times fa-sm text-white-50 mr-1"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('userEditForm');
    const nameInput = document.getElementById('name');
    const usernameInput = document.getElementById('username');
    const emailInput = document.getElementById('email');
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

    function validateRole() {
        // Skip validation if role select is disabled (editing own profile)
        if (roleSelect.disabled) {
            return true;
        }

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

    if (!roleSelect.disabled) {
        roleSelect.addEventListener('change', validateRole);
        roleSelect.addEventListener('blur', validateRole);
    }

    // Form submission validation
    form.addEventListener('submit', function(e) {
        const isNameValid = validateName();
        const isUsernameValid = validateUsername();
        const isEmailValid = validateEmail();
        const isRoleValid = validateRole();

        if (!isNameValid || !isUsernameValid || !isEmailValid || !isRoleValid) {
            e.preventDefault();
            
            // Focus on first invalid field
            if (!isNameValid) {
                nameInput.focus();
            } else if (!isUsernameValid) {
                usernameInput.focus();
            } else if (!isEmailValid) {
                emailInput.focus();
            } else if (!isRoleValid) {
                roleSelect.focus();
            }
        }
    });
});
</script>
@endpush
