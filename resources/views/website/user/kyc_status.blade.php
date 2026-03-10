@extends('website.layouts.dashboard')

@section('title', 'KYC Status | LaraBids')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow border-0 rounded-4 overflow-hidden text-center p-5">
                <div class="mb-4">
                    @if($kyc->status == 'pending')
                        <div class="display-1 text-warning mb-3">
                            <i class="fas fa-clock fa-spin-slow"></i>
                        </div>
                        <h3 class="fw-bold text-dark">Verification Pending</h3>
                        <p class="text-muted">Your KYC documents have been submitted and are currently being reviewed by our team. This usually takes 24-48 hours.</p>
                        <span class="badge bg-warning-subtle text-warning px-3 py-2 rounded-pill">Status: Pending</span>
                    @elseif($kyc->status == 'approved')
                        <div class="display-1 text-success mb-3">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h3 class="fw-bold text-dark">Verification Successful</h3>
                        <p class="text-muted">Congratulations! Your identity has been verified. You now have full access to all bidding and auction features.</p>
                        <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">Status: Approved</span>
                    @elseif($kyc->status == 'rejected')
                        <div class="display-1 text-danger mb-3">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <h3 class="fw-bold text-dark">Verification Rejected</h3>
                        <p class="text-muted text-danger fw-semibold">Note from Admin: {{ $kyc->admin_note ?? 'Documents were unclear or invalid.' }}</p>
                        <p class="text-muted">Please resubmit your documents with corrected information.</p>
                        <div class="mt-4">
                            <a href="{{ route('user.kyc.form') }}" class="btn btn-primary rounded-pill px-4">
                                <i class="fas fa-redo me-2"></i> Resubmit KYC
                            </a>
                        </div>
                        <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill mt-3">Status: Rejected</span>
                    @endif
                </div>

                <div class="mt-5 pt-4 border-top">
                    <div class="row text-start g-3">
                        <div class="col-6">
                            <div class="small text-muted text-uppercase fw-bold">Submitted Name</div>
                            <div class="fw-semibold">{{ $kyc->full_name }}</div>
                        </div>
                        <div class="col-6">
                            <div class="small text-muted text-uppercase fw-bold">ID Type</div>
                            <div class="fw-semibold text-capitalize">{{ str_replace('_', ' ', $kyc->id_type) }}</div>
                        </div>
                        <div class="col-12">
                            <div class="small text-muted text-uppercase fw-bold">Submitted On</div>
                            <div class="fw-semibold">{{ $kyc->created_at->format('M d, Y h:i A') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .fa-spin-slow {
        animation: fa-spin 3s infinite linear;
    }
</style>
@endsection
