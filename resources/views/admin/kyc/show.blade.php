@extends('admin.layouts.admin')

@section('title', 'KYC Details - LaraBids')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">KYC Verification Details</h1>
        <a href="{{ route('admin.kyc.index') }}" class="btn btn-secondary btn-sm shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to List
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-4">
            <!-- User Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">User Information</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <img class="img-profile rounded-circle mb-3" width="100" height="100" style="object-fit: cover;" src="{{ $kyc->user->avatar_url }}">
                        <h5 class="font-weight-bold">{{ $kyc->user->name }}</h5>
                        <p class="text-muted small">{{ $kyc->user->email }}</p>
                    </div>
                    <hr>
                    <div class="small">
                        <p><strong>Username:</strong> {{ $kyc->user->username }}</p>
                        <p><strong>Joined:</strong> {{ $kyc->user->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Verification Action -->
            @if($kyc->status == 'pending')
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Take Action</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.kyc.approve', $kyc->id) }}" method="POST" class="mb-3">
                            @csrf
                            <button type="submit" class="btn btn-success btn-block">
                                <i class="fas fa-check mr-2"></i> Approve KYC
                            </button>
                        </form>
                        
                        <hr>
                        
                        <form action="{{ route('admin.kyc.reject', $kyc->id) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="admin_note" class="small font-weight-bold">Rejection Reason</label>
                                <textarea name="admin_note" id="admin_note" class="form-control" rows="3" placeholder="Explain why this was rejected..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-danger btn-block">
                                <i class="fas fa-times mr-2"></i> Reject KYC
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Verification Status</h6>
                    </div>
                    <div class="card-body text-center">
                        @if($kyc->status == 'approved')
                            <div class="h3 text-success mb-0"><i class="fas fa-check-circle"></i></div>
                            <div class="text-success font-weight-bold">Approved</div>
                        @else
                            <div class="h3 text-danger mb-0"><i class="fas fa-times-circle"></i></div>
                            <div class="text-danger font-weight-bold">Rejected</div>
                            @if($kyc->admin_note)
                                <p class="small text-muted mt-2"><strong>Note:</strong> {{ $kyc->admin_note }}</p>
                            @endif
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Submitted KYC Details</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="small font-weight-bold text-uppercase text-muted">Full Name</label>
                            <p class="h5">{{ $kyc->full_name }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="small font-weight-bold text-uppercase text-muted">Date of Birth</label>
                            <p class="h5">{{ \Carbon\Carbon::parse($kyc->date_of_birth)->format('M d, Y') }}</p>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label class="small font-weight-bold text-uppercase text-muted">Address</label>
                            <p>{{ $kyc->address }}</p>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="small font-weight-bold text-uppercase text-muted">ID Type</label>
                            <p class="text-capitalize">{{ str_replace('_', ' ', $kyc->id_type) }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="small font-weight-bold text-uppercase text-muted">ID Number</label>
                            <p>{{ $kyc->id_number }}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="row mt-4">
                        <div class="col-md-6 mb-4">
                            <label class="small font-weight-bold text-uppercase text-muted d-block mb-2">ID Document</label>
                            @if(Str::endsWith($kyc->id_document, '.pdf'))
                                <div class="bg-light p-3 text-center rounded border">
                                    <i class="fas fa-file-pdf fa-3x text-danger mb-2"></i>
                                    <p class="small mb-0">PDF Document</p>
                                    <a href="{{ asset('storage/' . $kyc->id_document) }}" target="_blank" class="btn btn-sm btn-primary mt-2">View PDF</a>
                                </div>
                            @else
                                <a href="{{ asset('storage/' . $kyc->id_document) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $kyc->id_document) }}" class="img-fluid rounded border shadow-sm" alt="ID Document">
                                </a>
                            @endif
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="small font-weight-bold text-uppercase text-muted d-block mb-2">Selfie with ID</label>
                            <a href="{{ asset('storage/' . $kyc->selfie_image) }}" target="_blank">
                                <img src="{{ asset('storage/' . $kyc->selfie_image) }}" class="img-fluid rounded border shadow-sm" alt="Selfie Image">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
