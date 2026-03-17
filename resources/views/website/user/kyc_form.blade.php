@extends('website.layouts.dashboard')

@section('title', 'Submit KYC | LaraBids')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9 col-xl-7">
        <div class="card card-elite px-4 px-md-5 py-4 border-0 shadow-lg">
            <div class="text-center mb-4">
                <div class="bg-primary bg-opacity-10 p-3 rounded-circle d-inline-block mb-3">
                    <i class="fas fa-id-card text-primary fs-3"></i>
                </div>
                <h4 class="fw-bolder text-dark mb-1">Identity Verification</h4>
                <p class="text-muted small mb-0">Follow the steps below to verify your account.</p>
            </div>

            @if(session('error'))
                <div class="alert alert-danger border-0 shadow-sm mb-4 d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle me-3"></i>
                    <div>{{ session('error') }}</div>
                </div>
            @endif

            <form action="{{ route('user.kyc.submit') }}" method="POST" enctype="multipart/form-data" id="kycSubmitForm" novalidate>
                @csrf
                
                <div class="row g-3">
                    <!-- Personal Info -->
                    <div class="col-12">
                        <h6 class="fw-bold text-dark border-start border-primary border-4 ps-3 mb-3">Personal Details</h6>
                    </div>

                    <div class="col-md-6">
                        <label for="full_name" class="form-label fw-bold text-dark small">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="full_name" id="full_name" 
                               class="form-control form-control-lg @error('full_name') is-invalid @enderror" 
                               value="{{ old('full_name') }}" placeholder="As per official documents">
                        <div class="client-error text-danger small mt-1" style="display:none;"></div>
                    </div>

                    <div class="col-md-6">
                        <label for="date_of_birth" class="form-label fw-bold text-dark small">Date of Birth <span class="text-danger">*</span></label>
                        <input type="date" name="date_of_birth" id="date_of_birth" 
                               class="form-control form-control-lg @error('date_of_birth') is-invalid @enderror" 
                               value="{{ old('date_of_birth') }}">
                        <div class="client-error text-danger small mt-1" id="error_date_of_birth" style="display:none;"></div>
                        @error('date_of_birth')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="gender" class="form-label fw-bold text-dark small">Gender <span class="text-danger">*</span></label>
                        <select name="gender" id="gender" class="form-select form-select-lg @error('gender') is-invalid @enderror">
                            <option value="" selected disabled>Select Gender</option>
                            <option value="male" {{ (old('gender') ?? ($kyc->gender ?? '')) == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ (old('gender') ?? ($kyc->gender ?? '')) == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ (old('gender') ?? ($kyc->gender ?? '')) == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        <div class="client-error text-danger small mt-1" id="error_gender" style="display:none;"></div>
                        @error('gender')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="id_type" class="form-label fw-bold text-dark small">ID Document Type <span class="text-danger">*</span></label>
                        <select name="id_type" id="id_type" class="form-select form-select-lg @error('id_type') is-invalid @enderror">
                            <option value="" selected disabled>Select Type</option>
                            <option value="aadhaar" {{ (old('id_type') ?? ($kyc->id_type ?? '')) == 'aadhaar' ? 'selected' : '' }}>Aadhaar Card</option>
                            <option value="pan" {{ (old('id_type') ?? ($kyc->id_type ?? '')) == 'pan' ? 'selected' : '' }}>PAN Card</option>
                            <option value="passport" {{ (old('id_type') ?? ($kyc->id_type ?? '')) == 'passport' ? 'selected' : '' }}>Passport</option>
                            <option value="driving_license" {{ (old('id_type') ?? ($kyc->id_type ?? '')) == 'driving_license' ? 'selected' : '' }}>Driving License</option>
                        </select>
                        <div class="client-error text-danger small mt-1" id="error_id_type" style="display:none;"></div>
                        @error('id_type')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 mt-3">
                        <label for="id_number" class="form-label fw-bold text-dark small">Document ID Number <span class="text-danger">*</span></label>
                        <input type="text" name="id_number" id="id_number" 
                               class="form-control form-control-lg @error('id_number') is-invalid @enderror" 
                               value="{{ old('id_number') }}" placeholder="Enter your ID number">
                        <div class="client-error text-danger small mt-1" style="display:none;"></div>
                    </div>

                    <div class="col-12 mt-4">
                        <h6 class="fw-bold text-dark border-start border-primary border-4 ps-3 mb-3">Document Uploads</h6>
                    </div>

                    <div class="col-md-6">
                        <label for="id_document" class="form-label fw-bold text-dark small">Identity Document (Full View) <span class="text-danger">*</span></label>
                        <input type="file" name="id_document" id="id_document" 
                               class="form-control @error('id_document') is-invalid @enderror" 
                               accept="image/*,.pdf" onchange="previewFile(this, 'id_preview', 'error_id_document')">
                        <div class="client-error text-danger small mt-1" id="error_id_document" style="display:none;"></div>
                        @error('id_document')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <div id="id_preview" class="mt-2 d-none">
                            <div class="preview-card p-2 border rounded bg-white d-flex align-items-center gap-3">
                                <img src="" class="img-preview rounded shadow-sm" style="width: 50px; height: 50px; object-fit: cover; display: none;">
                                <div class="pdf-icon text-danger d-none" style="font-size: 1.5rem;">
                                    <i class="fas fa-file-pdf"></i>
                                </div>
                                <div class="small text-muted flex-grow-1">File selected</div>
                                <button type="button" class="btn-close small" onclick="this.parentElement.parentElement.classList.add('d-none'); document.getElementById('id_document').value=''; clearError(document.getElementById('id_document'))"></button>
                            </div>
                        </div>
                        <small class="text-muted mt-2 d-block small-tip"><i class="fas fa-info-circle me-1"></i> JPEG, PNG or PDF. Front & Back must be clearly visible. (Max 5MB)</small>
                    </div>

                    <div class="col-md-6">
                        <label for="selfie_image" class="form-label fw-bold text-dark small">Selfie with ID <span class="text-danger">*</span></label>
                        <input type="file" name="selfie_image" id="selfie_image" 
                               class="form-control @error('selfie_image') is-invalid @enderror" 
                               accept="image/*,.pdf" onchange="previewFile(this, 'selfie_preview')">
                        <div class="client-error text-danger small mt-1" style="display:none;"></div>
                        @error('selfie_image')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <div id="selfie_preview" class="mt-2 d-none">
                            <div class="preview-card p-2 border rounded bg-white d-flex align-items-center gap-3">
                                <img src="" class="img-preview rounded shadow-sm" style="width: 50px; height: 50px; object-fit: cover; display: none;">
                                <div class="pdf-icon text-danger d-none" style="font-size: 1.5rem;">
                                    <i class="fas fa-file-pdf"></i>
                                </div>
                                <div class="small text-muted flex-grow-1">File selected</div>
                                <button type="button" class="btn-close small" onclick="this.parentElement.parentElement.classList.add('d-none'); document.getElementById('selfie_image').value=''; clearError(document.getElementById('selfie_image'))"></button>
                            </div>
                        </div>
                        <small class="text-muted mt-2 d-block small-tip"><i class="fas fa-info-circle me-1"></i> Selfie with Full ID. Face and ID must be clear. (Max 5MB)</small>
                    </div>

                    <div class="col-md-6">
                        <label for="signature_image" class="form-label fw-bold text-dark small">Digital Signature <span class="text-danger">*</span></label>
                        <input type="file" name="signature_image" id="signature_image" 
                               class="form-control @error('signature_image') is-invalid @enderror" 
                               accept="image/*,.pdf" onchange="previewFile(this, 'signature_preview')">
                        <div class="client-error text-danger small mt-1" style="display:none;"></div>
                        @error('signature_image')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <div id="signature_preview" class="mt-2 d-none">
                            <div class="preview-card p-2 border rounded bg-white d-flex align-items-center gap-3">
                                <img src="" class="img-preview rounded shadow-sm" style="width: 50px; height: 50px; object-fit: cover; display: none;">
                                <div class="pdf-icon text-danger d-none" style="font-size: 1.5rem;">
                                    <i class="fas fa-file-pdf"></i>
                                </div>
                                <div class="small text-muted flex-grow-1">File selected</div>
                                <button type="button" class="btn-close small" onclick="this.parentElement.parentElement.classList.add('d-none'); document.getElementById('signature_image').value=''; clearError(document.getElementById('signature_image'))"></button>
                            </div>
                        </div>
                        <small class="text-muted mt-2 d-block small-tip"><i class="fas fa-info-circle me-1"></i> JPEG, PNG or PDF of your signature. (Max 5MB)</small>
                    </div>

                    <div class="col-12 mt-4 pt-4 border-top">
                        <div class="form-check d-flex align-items-center gap-3">
                            <input class="form-check-input mt-0 shadow-none cursor-pointer" type="checkbox" name="legal_declaration" id="legal_declaration" style="width: 1.2rem; height: 1.2rem;">
                            <label class="form-check-label small text-dark fw-bold cursor-pointer" for="legal_declaration">
                                I solemnly declare that all information and documents provided are true and original.
                            </label>
                        </div>
                        <div class="client-error text-danger small mt-2" style="display:none;"></div>
                        @error('legal_declaration')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-primary btn-lg w-100 py-3 rounded-pill shadow-sm fw-bold" id="submitBtn">
                            <i class="fas fa-shield-alt me-2"></i> Submit for Review
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .card-elite { border-radius: 20px; background: #ffffff; }
    .form-control-lg, .form-select-lg { font-size: 0.95rem; padding: 0.8rem 1rem; }
    .form-control, .form-select, .input-group-text { border: 1px solid #d1d3e2 !important; }
    .form-control:focus, .form-select:focus { 
        background-color: #fff !important; 
        border-color: #bac8f3 !important; 
        box-shadow: none !important; 
    }
    .preview-card { border-style: dashed !important; background: #f8f9fc !important; }
    .small-tip { font-size: 0.75rem; opacity: 0.8; }
</style>
@endpush

@push('scripts')
<script>
function previewFile(input, previewId, errorId) {
    const previewContainer = document.getElementById(previewId);
    if (!previewContainer) return;

    const imgPreview = previewContainer.querySelector('.img-preview');
    const pdfIcon = previewContainer.querySelector('.pdf-icon');
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const reader = new FileReader();
        
        previewContainer.classList.remove('d-none');
        
        if (file.type === 'application/pdf') {
            if (pdfIcon) pdfIcon.classList.remove('d-none');
            if (imgPreview) imgPreview.classList.add('d-none');
        } else if (file.type.startsWith('image/')) {
            if (pdfIcon) pdfIcon.classList.add('d-none');
            reader.onload = function(e) {
                if (imgPreview) {
                    imgPreview.src = e.target.result;
                    imgPreview.classList.remove('d-none');
                }
            };
            reader.readAsDataURL(file);
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('kycSubmitForm');
    if (!form) return;
    const submitBtn = document.getElementById('submitBtn');
    
    const showError = (input, message) => {
        const parent = input.closest('.col-12, .col-md-6');
        if (!parent) return;
        const errorDiv = parent.querySelector('.client-error');
        input.classList.add('is-invalid');
        if (errorDiv) {
            errorDiv.textContent = message;
            errorDiv.style.display = 'block';
        }
    };

    const clearError = (input) => {
        const parent = input.closest('.col-12, .col-md-6');
        if (!parent) return;
        const errorDiv = parent.querySelector('.client-error');
        input.classList.remove('is-invalid');
        if (errorDiv) {
            errorDiv.textContent = '';
            errorDiv.style.display = 'none';
        }
        const serverError = parent.querySelector('.invalid-feedback');
        if (serverError) serverError.style.display = 'none';
    };

    const idRules = {
        'aadhaar': { length: 12, placeholder: 'e.g. 1234 5678 9012', label: '12 Digit Aadhaar Number' },
        'pan': { length: 10, placeholder: 'e.g. ABCDE1234F', label: '10 Character PAN Number' },
        'passport': { length: 8, placeholder: 'e.g. A1234567', label: '8 Character Passport Number' },
        'driving_license': { length: 15, placeholder: 'Dashed are not required', label: '15 Char DL Number' }
    };

    const idType = document.getElementById('id_type');
    const idNumber = document.getElementById('id_number');

    if (idType) {
        idType.addEventListener('change', function() {
            const rule = idRules[this.value];
            if (rule && idNumber) {
                idNumber.placeholder = rule.placeholder;
                idNumber.maxLength = rule.length;
                const label = idNumber.previousElementSibling;
                if (label) label.innerHTML = `Document ID Number (${rule.label}) <span class="text-danger">*</span>`;
            }
        });
    }

    const validateField = (input) => {
        if (!input || input.type === 'hidden') return true;
        
        let isValid = true;
        let message = '';

        if (input.id === 'full_name') {
            if (input.value.trim().length < 3) {
                message = 'Full name must be at least 3 characters.';
                isValid = false;
            }
        } else if (input.id === 'date_of_birth') {
            if (!input.value) {
                message = 'Date of birth is required.';
                isValid = false;
            } else {
                const age = new Date().getFullYear() - new Date(input.value).getFullYear();
                if (age < 18) {
                    message = 'You must be at least 18 years old.';
                    isValid = false;
                }
            }
        } else if (input.id === 'gender') {
            if (!input.value) {
                message = 'Please select your gender.';
                isValid = false;
            }
        } else if (input.id === 'legal_declaration') {
            if (!input.checked) {
                message = 'You must agree to the declaration.';
                isValid = false;
            }
        } else if (input.id === 'id_type') {
            if (!input.value) {
                message = 'Please select a document type.';
                isValid = false;
            }
        } else if (input.id === 'id_number') {
            const rule = idRules[idType.value];
            if (!input.value.trim()) {
                message = 'ID number is required.';
                isValid = false;
            } else if (rule && input.value.trim().length !== rule.length) {
                message = `Should be exactly ${rule.length} characters.`;
                isValid = false;
            }
        } else if (input.type === 'file') {
            if (input.files.length === 0) {
                // For updates, file might not be required if already exists, but here we enforce
                message = 'This file is required.';
                isValid = false;
            } else {
                const file = input.files[0];
                if (file.size > 5 * 1024 * 1024) {
                    message = 'File size exceeds 5MB.';
                    isValid = false;
                }
            }
        }

        if (!isValid) showError(input, message);
        else clearError(input);
        return isValid;
    };

    form.addEventListener('submit', function(e) {
        let isFormValid = true;
        form.querySelectorAll('input:not([type="hidden"]), select').forEach(input => {
            if (!validateField(input)) isFormValid = false;
        });

        if (!isFormValid) {
            e.preventDefault();
            const firstError = document.querySelector('.is-invalid');
            if (firstError) firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        } else if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Submitting...';
        }
    });

    form.querySelectorAll('input, select').forEach(input => {
        input.addEventListener('input', () => clearError(input));
        input.addEventListener('blur', () => {
            if(input.type !== 'file') validateField(input);
        });
        input.addEventListener('change', () => validateField(input));
    });
});
</script>
@endpush
@endsection



