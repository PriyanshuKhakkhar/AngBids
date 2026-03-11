@extends('website.layouts.dashboard')

@section('title', 'Profile Settings | LaraBids')

@section('content')

<h2 class="h3 text-dark fw-bold mb-4">Profile Settings</h2>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card card-elite p-4 shadow-sm border-light">
            <h5 class="text-primary fw-bold mb-4">Personal Information</h5>
            
            @if (session('status') === 'profile-updated')
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    Profile updated successfully.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('status') === 'avatar-deleted')
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    Profile picture removed successfully.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('patch')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label text-dark fw-bold small">Full Name</label>
                        <input type="text" class="form-control form-control-elite @error('name') is-invalid @enderror" id="name" name="name" 
                            value="{{ old('name', auth()->user()->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="username" class="form-label text-dark fw-bold small">Username</label>
                        <div class="input-group">
                            <span class="input-group-text bg-secondary-subtle border-light text-muted small">@</span>
                            <input type="text" class="form-control form-control-elite @error('username') is-invalid @enderror" id="username" name="username" 
                                value="{{ old('username', auth()->user()->username) }}" required placeholder="your_handle">
                        </div>
                        @error('username')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label text-dark fw-bold small">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-secondary-subtle border-light"><i class="fas fa-lock text-muted small"></i></span>
                            <input type="email" class="form-control form-control-elite bg-secondary-subtle border-light text-muted" id="email" 
                                value="{{ auth()->user()->email }}" readonly disabled style="cursor: not-allowed;">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="phone" class="form-label text-dark fw-bold small">Phone Number</label>
                        <input type="tel" class="form-control form-control-elite @error('phone') is-invalid @enderror" id="phone" name="phone" 
                            value="{{ old('phone', auth()->user()->phone) }}" placeholder="+1 (555) 123-4567">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="location" class="form-label text-dark fw-bold small">Location</label>
                        <input type="text" class="form-control form-control-elite @error('location') is-invalid @enderror" id="location" name="location" 
                            value="{{ old('location', auth()->user()->location) }}" placeholder="City, Country">
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label for="bio" class="form-label text-dark fw-bold small">Bio</label>
                        <textarea class="form-control form-control-elite @error('bio') is-invalid @enderror" id="bio" name="bio" rows="3" 
                            placeholder="Tell us a bit about yourself...">{{ old('bio', auth()->user()->bio) }}</textarea>
                        @error('bio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12 d-none">
                        <input type="file" id="avatar-input" name="avatar" accept="image/*">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary px-4">Save Changes</button>
                    </div>
                </div>
            </form>
        </div>

        @if(is_null(auth()->user()->google_id))
        <div class="card card-elite p-4 mt-4 shadow-sm border-light">
            <h5 class="text-primary fw-bold mb-4">Change Password</h5>

            @if (session('status') === 'password-updated')
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    Password updated successfully.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form method="post" action="{{ route('password.update') }}">
                @csrf
                @method('put')

                <div class="row g-3">
                    <div class="col-12">
                        <label for="update_password_current_password" class="form-label text-dark fw-bold small">Current Password</label>
                        <input type="password" class="form-control form-control-elite @error('current_password', 'updatePassword') is-invalid @enderror" 
                            id="update_password_current_password" name="current_password" autocomplete="current-password">
                        @error('current_password', 'updatePassword')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="update_password_password" class="form-label text-dark fw-bold small">New Password</label>
                        <input type="password" class="form-control form-control-elite @error('password', 'updatePassword') is-invalid @enderror" 
                            id="update_password_password" name="password" autocomplete="new-password">
                        @error('password', 'updatePassword')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="update_password_password_confirmation" class="form-label text-dark fw-bold small">Confirm Password</label>
                        <input type="password" class="form-control form-control-elite @error('password_confirmation', 'updatePassword') is-invalid @enderror" 
                            id="update_password_password_confirmation" name="password_confirmation" autocomplete="new-password">
                        @error('password_confirmation', 'updatePassword')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary px-4">Update Password</button>
                    </div>
                </div>
            </form>
        </div>
        @else
        <div class="card card-elite p-4 mt-4 shadow-sm border-light bg-light">
            <div class="d-flex align-items-center mb-0">
                <i class="fab fa-google fa-2x text-danger me-3"></i>
                <div>
                    <h5 class="text-dark fw-bold mb-1">Google Account</h5>
                    <p class="text-muted small mb-0">You are logged in using Google. Password changes are managed by your Google account.</p>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        <div class="card card-elite p-4 text-center shadow-sm border-light">
            <div class="position-relative d-inline-block mx-auto mb-3">
                <img src="{{ auth()->user()->avatar_url }}"
                    class="rounded-circle border border-light shadow-sm" width="120" height="120" style="object-fit: cover;" alt="Avatar" id="avatar-preview">
                <label for="avatar-input" class="btn btn-sm btn-primary rounded-circle position-absolute bottom-0 end-0 p-2" style="cursor: pointer;">
                    <i class="bi bi-camera"></i>
                </label>
            </div>
            <h5 class="text-dark fw-bold mb-0">{{ auth()->user()->username }}</h5>
            <p class="text-secondary small mb-3">{{ auth()->user()->email }}</p>
            <div class="d-flex flex-column gap-2 align-items-center">
                <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3 w-75" onclick="document.getElementById('avatar-input').click()">Change Avatar</button>
                
                @if(auth()->user()->avatar)
                    <form action="{{ route('profile.avatar.destroy') }}" method="POST" onsubmit="return confirm('Are you sure you want to remove your profile picture?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-link text-danger text-decoration-none small p-0 fw-bold">Delete Avatar</button>
                    </form>
                @endif
            </div>
            @error('avatar')
                <div class="text-danger small mt-2">{{ $message }}</div>
            @enderror
        </div>

        <div class="card card-elite p-4 mt-4 shadow-sm border-light">
            <h6 class="text-primary fw-bold mb-3">Identity Verification</h6>
            
            @if(auth()->user()->isAdmin() || auth()->user()->isSuperAdmin())
                <div class="alert alert-info py-2 px-3 border-0 small mb-0">
                    <i class="fas fa-shield-alt me-2"></i> Administrative accounts are automatically verified for system operations.
                </div>
            @else
                @php $kyc = auth()->user()->kyc; @endphp
                
                @if($kyc)
                    @if($kyc->status === 'approved')
                        <div class="text-center py-2">
                            <div class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-4 py-2 mb-3">
                                <i class="fas fa-check-circle me-1"></i> Account Verified
                            </div>
                            <p class="text-muted small mb-0">Your identity has been verified. You have full access to all features.</p>
                        </div>
                    @elseif($kyc->status === 'pending')
                        <div class="text-center py-2">
                            <div class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 rounded-pill px-4 py-2 mb-3">
                                <i class="fas fa-clock me-1"></i> Verification Pending
                            </div>
                            <p class="text-muted small mb-0">Your documents are under review. This usually takes 24-48 hours.</p>
                            <a href="{{ route('user.kyc.status') }}" class="btn btn-link btn-sm text-primary text-decoration-none mt-2">View Submission</a>
                        </div>
                    @elseif($kyc->status === 'rejected')
                        <div class="text-center py-2">
                            <div class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill px-4 py-2 mb-3">
                                <i class="fas fa-times-circle me-1"></i> Verification Rejected
                            </div>
                            @if($kyc->rejection_reason)
                                <div class="alert alert-danger bg-danger bg-opacity-10 border-0 small text-start mb-3">
                                    <strong>Reason:</strong> {{ $kyc->rejection_reason }}
                                </div>
                            @endif
                            <a href="{{ route('user.kyc.form') }}" class="btn btn-primary btn-sm rounded-pill px-4 w-100">Re-submit Documents</a>
                        </div>
                    @endif
                @else
                    <div class="text-center py-2">
                        <div class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 rounded-pill px-4 py-2 mb-3">
                            <i class="fas fa-id-card me-1"></i> Not Verified
                        </div>
                        <p class="text-muted small mb-3">To place bids or create auctions, you must verify your identity.</p>
                        <a href="{{ route('user.kyc.form') }}" class="btn btn-primary btn-sm rounded-pill px-4 w-100">Start Verification</a>
                    </div>
                @endif
            @endif
        </div>

        <div class="card card-elite p-4 mt-4 shadow-sm border-light">
            <h6 class="text-primary fw-bold mb-3">Account Statistics</h6>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-dark small">Member Since</span>
                <span class="text-primary small fw-bold">{{ auth()->user()->created_at->format('M Y') }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-dark small">Total Bids</span>
                <span class="text-primary small fw-bold">{{ auth()->user()->bids()->count() }}</span>
            </div>
            <div class="d-flex justify-content-between">
                <span class="text-dark small">Items Won</span>
                <span class="text-success small fw-bold">{{ auth()->user()->getWonAuctionsCount() }}</span>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('avatar-input').onchange = function (evt) {
        const [file] = this.files
        if (file) {
            document.getElementById('avatar-preview').src = URL.createObjectURL(file)
        }
    }
</script>


@endsection
