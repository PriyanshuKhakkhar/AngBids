@extends('website.layouts.app')

@section('title', 'Edit Auction | LaraBids')

@section('content')

@php
    $hasBids = $auction->bids()->exists();
@endphp

<!-- Hero Header -->
<section class="hero-section text-center text-white d-flex align-items-center mb-5">
    <div class="container" data-aos="fade-up">
        <span class="badge bg-white text-primary fw-bold px-3 py-2 rounded-pill mb-3 shadow-sm">
            ✏️ EDIT YOUR AUCTION
        </span>
        <h1 class="display-3 fw-bold mb-3">Refine Your <span class="text-white">Listing</span></h1>
        <p class="lead opacity-75 mx-auto" style="max-width: 700px;">
            Update your item details. Note that some fields are locked once bidding has started to ensure fairness.
        </p>
    </div>
</section>

<section class="pb-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8" data-aos="fade-right">
                <div class="card card-elite p-4 p-md-5 border-0 shadow-lg">
                    <form action="{{ route('auctions.update', $auction->id) }}" method="POST" enctype="multipart/form-data" id="auctionEditForm" novalidate>
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-4">
                            <!-- Basic Info -->
                            <div class="col-12">
                                <h5 class="fw-bold text-dark border-start border-primary border-4 ps-3 mb-4">Basic Information</h5>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold text-dark small text-uppercase">Item Title</label>
                                <input type="text" name="title" class="form-control form-control-lg bg-light border-0 shadow-none @error('title') is-invalid @enderror" 
                                    placeholder="What are you selling?" value="{{ old('title', $auction->title) }}">
                                @error('title')
                                    <div class="invalid-feedback" data-server-error>{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold text-dark small text-uppercase mb-3">Item Category <span class="text-danger">*</span></label>
                                
                                <input type="hidden" name="category_id" id="selected_category_id" value="{{ old('category_id', $auction->category_id) }}">
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <select id="mainCategorySelect" class="form-select form-select-lg bg-light border-0 shadow-none" {{ $hasBids ? 'disabled' : '' }}>
                                            <option value="" disabled selected>Choose Main Category</option>
                                            @foreach($categories as $parent)
                                                @php
                                                    $isCurrentParent = $auction->category->parent_id == $parent->id || $auction->category_id == $parent->id;
                                                @endphp
                                                <option value="{{ $parent->id }}" 
                                                    {{ (old('category_id', $auction->category_id) && $isCurrentParent) ? 'selected' : '' }}>
                                                    {{ $parent->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <div id="subCategoryDropdownWrapper" style="{{ $auction->category->parent_id ? '' : 'display: none;' }}">
                                            <select id="subCategorySelect" class="form-select form-select-lg bg-light border-0 shadow-none" {{ $hasBids ? 'disabled' : '' }}>
                                                <option value="" disabled>Choose Sub-Category</option>
                                                @if($auction->category->parent_id)
                                                    @foreach($auction->category->parent->children as $child)
                                                        <option value="{{ $child->id }}" {{ $auction->category_id == $child->id ? 'selected' : '' }}>
                                                            {{ $child->name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                @if($hasBids)
                                    <small class="text-muted"><i class="fas fa-lock me-1"></i> Category is locked because this auction already has bids.</small>
                                @endif

                                @error('category_id')
                                    <div class="invalid-feedback d-block" data-server-error>{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Pass child data to JS -->
                            <script>
                                window.categoryTree = @json($categoryTree);
                            </script>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark small text-uppercase">Starting Price (₹)</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light border-0 text-primary fw-bold border-end">₹</span>
                                    <input type="number" name="starting_price" step="0.01" min="0.01" class="form-control bg-light border-0 shadow-none @error('starting_price') is-invalid @enderror" 
                                        placeholder="0.00" value="{{ old('starting_price', $auction->starting_price) }}" {{ $hasBids ? 'disabled' : '' }}>
                                </div>
                                @if($hasBids)
                                    <small class="text-muted"><i class="fas fa-lock me-1"></i> Starting price is locked due to active bids.</small>
                                @endif
                                @error('starting_price')
                                    <div class="invalid-feedback d-block" data-server-error>{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark small text-uppercase">Min Bid Increment (₹)</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light border-0 text-primary fw-bold border-end">₹</span>
                                    <input type="number" name="min_increment" step="1" min="1" max="100000.00" class="form-control bg-light border-0 shadow-none @error('min_increment') is-invalid @enderror" 
                                        placeholder="100" value="{{ old('min_increment', $auction->min_increment ?? '100') }}">
                                </div>
                                <small class="text-muted">Minimum amount each next bid must increase by.</small>
                                @error('min_increment')
                                    <div class="invalid-feedback d-block" data-server-error>{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="col-12">
                                <label class="form-label fw-bold text-dark small text-uppercase">Description</label>
                                <textarea name="description" rows="5" class="form-control bg-light border-0 shadow-none @error('description') is-invalid @enderror" 
                                    placeholder="Describe your item in detail...">{{ old('description', $auction->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback" data-server-error>{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Timing & Media -->
                            <div class="col-12 mt-5">
                                <h5 class="fw-bold text-dark border-start border-primary border-4 ps-3 mb-4">Timing & Media</h5>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark small text-uppercase">Auction Start Date & Time</label>
                                <input type="datetime-local" name="start_time" class="form-control form-control-lg bg-light border-0 shadow-none @error('start_time') is-invalid @enderror" 
                                    value="{{ old('start_time', $auction->start_time->format('Y-m-d\TH:i')) }}" {{ $hasBids ? 'disabled' : '' }}>
                                <small class="text-muted">When should the bidding begin?</small>
                                @error('start_time')
                                    <div class="invalid-feedback" data-server-error>{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark small text-uppercase">Auction End Date & Time</label>
                                <input type="datetime-local" name="end_time" class="form-control form-control-lg bg-light border-0 shadow-none @error('end_time') is-invalid @enderror" 
                                    value="{{ old('end_time', $auction->end_time->format('Y-m-d\TH:i')) }}">
                                <small class="text-muted">When should the bidding conclude?</small>
                                @error('end_time')
                                    <div class="invalid-feedback" data-server-error>{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Images Management -->
                            <div class="col-12 mt-4">
                                <label class="form-label fw-bold text-dark small text-uppercase">Item Photos</label>
                                
                                <!-- Existing Images -->
                                <div class="row g-3 mb-3">
                                    @foreach($auction->images as $img)
                                        <div class="col-md-3 existing-image-container" id="existing-img-{{ $img->id }}">
                                            <div class="position-relative rounded-3 overflow-hidden border">
                                                <img src="{{ asset('storage/' . $img->image_path) }}" class="w-100 h-100 object-fit-cover" style="aspect-ratio: 1/1;">
                                                @if($img->is_primary)
                                                    <span class="badge bg-primary position-absolute top-0 start-0 m-2">Primary</span>
                                                @endif
                                                <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 shadow-sm delete-existing-img" 
                                                    data-id="{{ $img->id }}" onclick="removeExistingImage({{ $img->id }})">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div id="deletedImagesContainer"></div>

                                <div class="image-upload-wrapper mt-3" onclick="document.getElementById('imageInput').click()">
                                    <div class="upload-content text-center">
                                        <i class="fas fa-plus-circle fa-2x text-primary mb-2"></i>
                                        <h6 class="fw-bold mb-1">Add More Photos</h6>
                                        <p class="text-muted small mb-0">Click to browse. Max 5 total photos.</p>
                                    </div>
                                    <input type="file" name="images[]" multiple accept="image/jpeg,image/png,image/jpg,image/gif" class="d-none" id="imageInput">
                                </div>
                                <small class="text-muted d-block mt-2" id="imageLimitInfo">
                                    You have {{ $auction->images->count() }} images. You can add {{ max(0, 5 - $auction->images->count()) }} more.
                                </small>
                                <input type="hidden" name="primary_image_index" id="primaryImageIndex" value="0">
                                
                                <div id="imagePreviewGrid" class="image-preview-grid">
                                    <!-- New previews will be injected here by JS -->
                                </div>

                                @error('images')
                                    <div class="invalid-feedback d-block" data-server-error>{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mt-5">
                                <button type="submit" class="btn btn-primary btn-lg w-100 py-3 rounded-pill shadow fw-bold">
                                    💾 Save Changes
                                </button>
                                <a href="{{ route('user.my-auctions') }}" class="btn btn-link w-100 text-muted mt-2 text-decoration-none small">Cancel and Go Back</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Side Info -->
            <div class="col-lg-4 mt-5 mt-lg-0" data-aos="fade-left">
                <div class="sticky-top" style="top: 100px;">
                    <div class="card card-elite border-0 shadow-sm p-4 mb-4">
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-4">
                                <i class="fas fa-info-circle text-primary fs-4"></i>
                            </div>
                            <h5 class="mb-0 fw-bold text-dark">Editing Rules</h5>
                        </div>
                        <ul class="list-unstyled mb-0 small">
                            <li class="d-flex gap-3 mb-3">
                                <i class="fas fa-check-circle text-success mt-1"></i>
                                <div>You can update the **Title** and **Description** at any time.</div>
                            </li>
                            <li class="d-flex gap-3 mb-3">
                                <i class="fas fa-{{ $hasBids ? 'lock' : 'check-circle' }} text-{{ $hasBids ? 'warning' : 'success' }} mt-1"></i>
                                <div>**Starting Price** can only be changed if no bids have been placed.</div>
                            </li>
                            <li class="d-flex gap-3">
                                <i class="fas fa-clock text-primary mt-1"></i>
                                <div>You can extend the **End Time** if you need more exposure.</div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/image-upload.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/category-selection.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('assets/js/image-upload-manager.js') }}"></script>
<script src="{{ asset('assets/js/auction-form-validation.js') }}"></script>
<script src="{{ asset('assets/js/category-selection.js') }}"></script>
<script src="{{ asset('assets/js/auction-edit.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize modular auction edit logic
        initAuctionEdit({
            existingImageCount: {{ $auction->images->count() }}
        });
    });
</script>
@endpush

@endsection
