@extends('admin.layouts.admin')

@section('title', 'View Auction - ' . $auction->title)

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Auction Details</h1>
        <a href="{{ route('admin.auctions.index') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to List
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ $auction->title }}</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        @if($auction->image)
                            <img src="{{ asset('storage/' . $auction->image) }}" class="img-fluid rounded" style="max-height: 400px;" alt="{{ $auction->title }}">
                        @else
                            <div class="bg-light p-5 rounded">
                                <i class="fas fa-image fa-4x text-gray-300"></i>
                                <p class="text-gray-500 mt-2">No Image Provided</p>
                            </div>
                        @endif
                    </div>
                    <h5>Description</h5>
                    <p>{{ $auction->description }}</p>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Starting Price:</strong> ${{ number_format($auction->starting_price, 2) }}</p>
                            <p><strong>Current Price:</strong> ${{ number_format($auction->current_price, 2) }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Start Time:</strong> {{ $auction->start_time->format('M d, Y H:i') }}</p>
                            <p><strong>End Time:</strong> {{ $auction->end_time->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Auction Info</h6>
                </div>
                <div class="card-body">
                    <p><strong>ID:</strong> #{{ $auction->id }}</p>
                    <p><strong>Seller:</strong> {{ $auction->user->name }} ({{ $auction->user->email }})</p>
                    <p><strong>Status:</strong> 
                        <span class="badge badge-{{ $auction->status == 'active' ? 'success' : ($auction->status == 'closed' ? 'secondary' : ($auction->status == 'cancelled' ? 'danger' : 'warning')) }}">
                            {{ ucfirst($auction->status) }}
                        </span>
                    </p>
                    <p><strong>Created At:</strong> {{ $auction->created_at->format('M d, Y H:i') }}</p>
                    <hr>
                    <div class="d-flex flex-column">
                        @if($auction->status != 'cancelled' && $auction->status != 'closed' && $auction->status != 'draft')
                            <form action="{{ route('admin.auctions.cancel', $auction) }}" method="POST" class="mb-2 cancel-confirm">
                                @csrf
                                <button type="submit" class="btn btn-warning btn-block">
                                    <i class="fas fa-ban mr-2"></i> Cancel Auction
                                </button>
                            </form>
                        @endif
                        <form action="{{ route('admin.auctions.destroy', $auction) }}" method="POST" class="delete-confirm">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-block">
                                <i class="fas fa-trash mr-2"></i> Delete Auction
                            </button>
                        </form>
                    </div>
                    <div class="alert alert-info mt-3 small">
                        <i class="fas fa-info-circle mr-1"></i> Admins cannot edit auction details created by users, but can cancel or delete them if necessary.
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Cancel Confirm
        $('.cancel-confirm').submit(function(e) {
            e.preventDefault();
            var form = this;
            
            Swal.fire({
                title: 'Cancel Auction?',
                text: "This will stop bidding immediately.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f6c23e',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, cancel it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        // Delete Confirm
        $('.delete-confirm').submit(function(e) {
            e.preventDefault();
            var form = this;
            
            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
