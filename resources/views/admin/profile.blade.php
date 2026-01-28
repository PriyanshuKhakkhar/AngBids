@extends('admin.layouts.admin')

@section('title', 'Admin Profile - LaraBids')

@section('styles')
<style>
    .profile-cover {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        height: 150px;
        border-radius: 0.35rem 0.35rem 0 0;
        position: relative;
    }
    .profile-avatar {
        width: 140px;
        height: 140px;
        border: 5px solid #fff;
        border-radius: 50%;
        position: absolute;
        bottom: -70px;
        left: 50%;
        transform: translateX(-50%);
        background-color: #fff;
        object-fit: cover;
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15);
    }
    .profile-card {
        margin-top: -50px;
        padding-top: 60px;
    }
    .stat-card {
        transition: all 0.2s ease-in-out;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15) !important;
    }
    .nav-pills .nav-link.active, .nav-pills .show>.nav-link {
        background-color: #4e73df;
    }
    .nav-pills .nav-link {
        color: #5a5c69;
        font-weight: 600;
        border-radius: 0.35rem;
        padding: 0.75rem 1.25rem;
    }
    .nav-pills .nav-link:hover {
        background-color: #eaecf4;
        color: #4e73df;
    }
    input.form-control {
        border-radius: 0.35rem;
        padding: 0.75rem 1rem;
        height: auto;
    }
    label {
        font-weight: 600;
        color: #4e73df;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
</style>
@endsection

@section('content')
    <div class="container-fluid">

        <!-- Header Section -->
        <div class="row mb-4 align-items-center">
            <div class="col">
                <h1 class="h3 mb-0 text-gray-800">Administrator Profile</h1>
                <p class="text-muted small mb-0">Manage your personal information and security settings.</p>
            </div>
            <div class="col-auto">
                <span class="badge badge-primary p-2 shadow-sm">
                    <i class="fas fa-shield-alt mr-1"></i> {{ ucfirst($user->getRoleNames()->first() ?? 'Super Admin') }}
                </span>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success border-left-success shadow-sm alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger border-left-danger shadow-sm alert-dismissible fade show" role="alert">
                <ul class="mb-0 pl-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="row">

            <!-- Left Column: User Info & Stats -->
            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4 border-0">
                    <div class="card-body p-0">
                        <div class="profile-cover">
                            <img class="profile-avatar" src="{{ asset('admin-assets/img/undraw_profile.svg') }}" alt="User Avatar">
                        </div>
                        <div class="profile-card text-center pb-4 px-4">
                            <h4 class="font-weight-bold text-gray-900 mb-1">{{ $user->name }}</h4>
                            <p class="text-muted mb-3">{{ $user->email }}</p>

                            <hr>

                            <div class="row text-center mt-4">
                                <div class="col-4">
                                    <h5 class="font-weight-bold text-primary mb-0">12</h5>
                                    <small class="text-xs font-weight-bold text-uppercase text-muted">Auctions</small>
                                </div>
                                <div class="col-4">
                                    <h5 class="font-weight-bold text-success mb-0">5</h5>
                                    <small class="text-xs font-weight-bold text-uppercase text-muted">Active</small>
                                </div>
                                <div class="col-4">
                                    <h5 class="font-weight-bold text-warning mb-0">4.9</h5>
                                    <small class="text-xs font-weight-bold text-uppercase text-muted">Rating</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info Card -->
                <div class="card shadow mb-4 border-0">
                    <div class="card-header py-3 bg-white border-bottom-0">
                        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-info-circle mr-2"></i>About</h6>
                    </div>
                    <div class="card-body pt-0">
                        <p class="text-muted small mb-3">
                            Administrator with full access to manage auctions, users, and system settings.
                            Responsible for maintaining platform integrity and user satisfaction.
                        </p>
                        <div class="mb-2">
                            <small class="text-uppercase text-secondary font-weight-bold">Joined</small>
                            <div class="font-weight-bold text-gray-800">{{ $user->created_at->format('F d, Y') }}</div>
                        </div>
                        <div class="mb-2">
                            <small class="text-uppercase text-secondary font-weight-bold">Last Login</small>
                            <div class="font-weight-bold text-gray-800">{{ now()->subMinutes(12)->diffForHumans() }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Settings Form -->
            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4 border-0">
                    <div class="card-header py-3 bg-white d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Edit Profile Information</h6>
                        
                        <ul class="nav nav-pills card-header-pills" id="profileTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active py-1 px-3 small" id="info-tab" data-toggle="tab" href="#info" role="tab">Personal Info</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link py-1 px-3 small" id="security-tab" data-toggle="tab" href="#security" role="tab">Security</a>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.profile.update') }}">
                            @csrf
                            @method('PUT')
                            
                            <div class="tab-content" id="profileTabContent">
                                <!-- Personal Info Tab -->
                                <div class="tab-pane fade show active" id="info" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-6 mb-4">
                                            <div class="form-group">
                                                <label for="inputName">Full Name</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text bg-light border-right-0"><i class="fas fa-user text-gray-500"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control border-left-0 @error('name') is-invalid @enderror" 
                                                           id="inputName" name="name" 
                                                           value="{{ old('name', $user->name) }}" required>
                                                </div>
                                                @error('name')
                                                    <small class="text-danger font-weight-bold mt-1">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-4">
                                            <div class="form-group">
                                                <label for="inputEmail">Email Address</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text bg-light border-right-0"><i class="fas fa-envelope text-gray-500"></i></span>
                                                    </div>
                                                    <input type="email" class="form-control border-left-0 @error('email') is-invalid @enderror" 
                                                           id="inputEmail" name="email" 
                                                           value="{{ old('email', $user->email) }}" required>
                                                </div>
                                                @error('email')
                                                    <small class="text-danger font-weight-bold mt-1">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="alert alert-info border-0 shadow-sm">
                                                <i class="fas fa-info-circle mr-2"></i> <strong>Note:</strong> Changing your email address may require you to re-verify your account.
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Security Tab -->
                                <div class="tab-pane fade" id="security" role="tabpanel">
                                    <div class="mb-4">
                                        <h5 class="h6 text-gray-800 mb-2">Change Password</h5>
                                        <p class="small text-muted mb-4">Ensure your account is secure by using a strong password. Leave these fields blank if you don't want to change it.</p>
                                    </div>

                                    <div class="form-group mb-4">
                                        <label for="current_password">Current Password</label>
                                        <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                               id="current_password" name="current_password" 
                                               placeholder="Enter current password to save changes">
                                        @error('current_password')
                                            <small class="text-danger font-weight-bold mt-1">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-4">
                                                <label for="password">New Password</label>
                                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                                       id="password" name="password" 
                                                       placeholder="Min. 8 characters">
                                                @error('password')
                                                    <small class="text-danger font-weight-bold mt-1">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-4">
                                                <label for="password_confirmation">Confirm Password</label>
                                                <input type="password" class="form-control" 
                                                       id="password_confirmation" name="password_confirmation" 
                                                       placeholder="Re-enter new password">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <hr class="mt-4 mb-4">
                            
                            <div class="d-flex justify-content-end">
                                <button type="reset" class="btn btn-light shadow-sm mr-2 text-gray-600 font-weight-bold">Cancel</button>
                                <button type="submit" class="btn btn-primary shadow px-4 font-weight-bold">
                                    <i class="fas fa-save mr-2"></i> Save Changes
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
                
                <!-- Activity Log (Static for visual) -->
                <div class="card shadow mb-4 border-0">
                     <div class="card-header py-3 bg-white">
                        <h6 class="m-0 font-weight-bold text-primary">Recent Activity</h6>
                    </div>
                    <div class="card-body">
                        <div class="small">
                            <div class="mb-3 d-flex align-items-center">
                                <div class="bg-primary text-white rounded-circle p-2 mr-3 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                    <i class="fas fa-user-edit fa-xs"></i>
                                </div>
                                <div>
                                    <span class="font-weight-bold text-gray-800">Profile Updated</span>
                                    <div class="text-muted">You updated your profile information.</div>
                                    <div class="text-xs text-gray-500">Just now</div>
                                </div>
                            </div>
                            <div class="mb-0 d-flex align-items-center">
                                <div class="bg-success text-white rounded-circle p-2 mr-3 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                    <i class="fas fa-sign-in-alt fa-xs"></i>
                                </div>
                                <div>
                                    <span class="font-weight-bold text-gray-800">Logged In</span>
                                    <div class="text-muted">Successful login from 192.168.1.1</div>
                                    <div class="text-xs text-gray-500">12 minutes ago</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
@endsection
