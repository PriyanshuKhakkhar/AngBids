@extends('website.layouts.dashboard')

@section('title', 'Submit KYC | LaraBids')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-primary py-3">
                    <h5 class="mb-0 text-white fw-bold"><i class="fas fa-id-card me-2"></i> Identity Verification (KYC)</h5>
                </div>
                <div class="card-body p-4 p-lg-5">
                    <p class="text-muted mb-4 small">
                        To ensure a safe and secure bidding environment, we require all users to complete their Identity Verification. 
                        Please provide accurate information as per your official documents.
                    </p>

                    @if(session('error'))
                        <div class="alert alert-danger border-0 shadow-sm mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('user.kyc.submit') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row g-4">
                            <!-- Full Name -->
                            <div class="col-md-6">
                                <label for="full_name" class="form-label fw-semibold small text-uppercase">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="full_name" id="full_name" class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name') }}" placeholder="As per ID document" required>
                                @error('full_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Date of Birth -->
                            <div class="col-md-6">
                                <label for="date_of_birth" class="form-label fw-semibold small text-uppercase">Date of Birth <span class="text-danger">*</span></label>
                                <input type="date" name="date_of_birth" id="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror" value="{{ old('date_of_birth') }}" required>
                                @error('date_of_birth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Address -->
                            <div class="col-12">
                                <label for="address" class="form-label fw-semibold small text-uppercase">Residential Address <span class="text-danger">*</span></label>
                                <textarea name="address" id="address" rows="3" class="form-control @error('address') is-invalid @enderror" placeholder="Street, City, State, ZIP" required>{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- ID Type -->
                            <div class="col-md-6">
                                <label for="id_type" class="form-label fw-semibold small text-uppercase">ID Document Type <span class="text-danger">*</span></label>
                                <select name="id_type" id="id_type" class="form-select @error('id_type') is-invalid @enderror" required>
                                    <option value="" selected disabled>Select Document Type</option>
                                    <option value="aadhaar" {{ old('id_type') == 'aadhaar' ? 'selected' : '' }}>Aadhaar Card</option>
                                    <option value="pan" {{ old('id_type') == 'pan' ? 'selected' : '' }}>PAN Card</option>
                                    <option value="passport" {{ old('id_type') == 'passport' ? 'selected' : '' }}>Passport</option>
                                    <option value="driving_license" {{ old('id_type') == 'driving_license' ? 'selected' : '' }}>Driving License</option>
                                </select>
                                @error('id_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- ID Number -->
                            <div class="col-md-6">
                                <label for="id_number" class="form-label fw-semibold small text-uppercase">ID Number <span class="text-danger">*</span></label>
                                <input type="text" name="id_number" id="id_number" class="form-control @error('id_number') is-invalid @enderror" value="{{ old('id_number') }}" placeholder="Enter document number" required>
                                @error('id_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- ID Document Upload -->
                            <div class="col-md-6">
                                <label for="id_document" class="form-label fw-semibold small text-uppercase">Upload ID Document <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="file" name="id_document" id="id_document" class="form-control @error('id_document') is-invalid @enderror" accept="image/*,.pdf" required>
                                </div>
                                <div class="form-text small">JPEG, PNG or PDF (Max 2MB)</div>
                                @error('id_document')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Selfie Image Upload -->
                            <div class="col-md-6">
                                <label for="selfie_image" class="form-label fw-semibold small text-uppercase">Upload Selfie with ID <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="file" name="selfie_image" id="selfie_image" class="form-control @error('selfie_image') is-invalid @enderror" accept="image/*" required>
                                </div>
                                <div class="form-text small">JPEG or PNG (Max 2MB)</div>
                                @error('selfie_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill shadow-sm">
                                    <i class="fas fa-paper-plane me-2"></i> Submit for Verification
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
