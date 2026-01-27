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
                            <label for="email">Email Address <span class="text-danger">*</span></label>
                            <input type="text" name="email" class="form-control @error('email') is-invalid @enderror" id="email" value="{{ old('email', $user->email) }}">
                            <div class="invalid-feedback" id="email-error"></div>
                            @error('email')
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
                            <label for="role">User Role <span class="text-danger">*</span></label>
                            <select name="role" id="role" class="form-control @error('role') is-invalid @enderror" {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                <option value="" disabled>Select Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ (old('role') ? old('role') == $role->name : $user->hasRole($role->name)) ? 'selected' : '' }}>{{ ucfirst($role->name) }}</option>
                                @endforeach
                            </select>
                            @if($user->id === auth()->id())
                                <input type="hidden" name="role" value="{{ $user->roles->first()->name }}">
                                <small class="text-muted">You cannot change your own role.</small>
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
    const emailInput = document.getElementById('email');
    const roleSelect = document.getElementById('role');

    // Validation functions
    function validateName() {
        const value = nameInput.value.trim();
        const errorDiv = document.getElementById('name-error');
        
        if (value === '') {
            nameInput.classList.add('is-invalid');
            nameInput.classList.remove('is-valid');
            errorDiv.textContent = 'Full name is required.';
            return false;
        } else if (value.length < 2) {
            nameInput.classList.add('is-invalid');
            nameInput.classList.remove('is-valid');
            errorDiv.textContent = 'Name must be at least 2 characters.';
            return false;
        } else if (value.length > 255) {
            nameInput.classList.add('is-invalid');
            nameInput.classList.remove('is-valid');
            errorDiv.textContent = 'Name must not exceed 255 characters.';
            return false;
        } else {
            nameInput.classList.remove('is-invalid');
            nameInput.classList.add('is-valid');
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
            emailInput.classList.remove('is-valid');
            errorDiv.textContent = 'Email address is required.';
            return false;
        } else if (!emailRegex.test(value)) {
            emailInput.classList.add('is-invalid');
            emailInput.classList.remove('is-valid');
            errorDiv.textContent = 'Please enter a valid email address.';
            return false;
        } else if (value.length > 255) {
            emailInput.classList.add('is-invalid');
            emailInput.classList.remove('is-valid');
            errorDiv.textContent = 'Email must not exceed 255 characters.';
            return false;
        } else {
            emailInput.classList.remove('is-invalid');
            emailInput.classList.add('is-valid');
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
            roleSelect.classList.remove('is-valid');
            errorDiv.textContent = 'Please select a user role.';
            return false;
        } else {
            roleSelect.classList.remove('is-invalid');
            roleSelect.classList.add('is-valid');
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
        const isEmailValid = validateEmail();
        const isRoleValid = validateRole();

        if (!isNameValid || !isEmailValid || !isRoleValid) {
            e.preventDefault();
            
            // Focus on first invalid field
            if (!isNameValid) {
                nameInput.focus();
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
