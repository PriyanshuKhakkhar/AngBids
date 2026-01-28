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
                    
                    {{-- Display Cancellation Reason if Cancelled --}}
                    @if($auction->status == 'cancelled' && $auction->cancellation_reason)
                        <div class="alert alert-danger mt-3">
                            <strong>Cancellation Reason:</strong><br>
                            {{ $auction->cancellation_reason }}
                        </div>
                    @endif

                    <hr>
                    <div class="d-flex flex-column">
                        @if($auction->trashed())
                            {{-- Restore --}}
                            <form action="{{ route('admin.auctions.restore', $auction->id) }}" method="POST" class="mb-2">
                                @csrf
                                <button type="submit" class="btn btn-success btn-block">
                                    <i class="fas fa-trash-restore mr-2"></i> Restore Auction
                                </button>
                            </form>
                            {{-- Permanent Delete --}}
                            <button type="button" class="btn btn-danger btn-block mb-2 trigger-force-delete" data-url="{{ route('admin.auctions.force_delete', $auction->id) }}">
                                <i class="fas fa-times mr-2"></i> Permanently Delete
                            </button>
                        @elseif($auction->status == 'closed')
                             <div class="alert alert-secondary text-center">
                                <i class="fas fa-lock mr-1"></i> Auction Closed
                             </div>
                        @else
                            {{-- Actions for Pending, Active, or Cancelled --}}
                            
                            {{-- Approve/Re-Activate Button --}}
                            {{-- Show for Pending or Cancelled --}}
                            @if($auction->status == 'pending' || $auction->status == 'cancelled')
                                <form action="{{ route('admin.auctions.approve', $auction->id) }}" method="POST" class="mb-2">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-block">
                                        <i class="fas fa-check mr-2"></i> {{ $auction->status == 'cancelled' ? 'Re-Approve (Activate)' : 'Approve & Activate' }}
                                    </button>
                                </form>
                            @endif

                            {{-- Cancel Button --}}
                            {{-- Show for Pending or Active --}}
                            @if($auction->status == 'pending' || $auction->status == 'active')
                                <button type="button" class="btn btn-warning btn-block mb-2 trigger-cancel" data-url="{{ route('admin.auctions.cancel', $auction->id) }}">
                                    <i class="fas fa-ban mr-2"></i> Cancel Auction
                                </button>
                            @endif
                        @endif
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
        // Cancel Confirm with Reason
        $('.trigger-cancel').click(function() {
            var url = $(this).data('url');
            
            Swal.fire({
                title: 'Cancel Auction',
                input: 'textarea',
                inputLabel: 'Reason for cancellation',
                inputPlaceholder: 'Type your reason here...',
                inputAttributes: {
                    'aria-label': 'Type your reason here'
                },
                showCancelButton: true,
                confirmButtonText: 'Submit Cancellation',
                confirmButtonColor: '#f6c23e',
                showLoaderOnConfirm: true,
                preConfirm: (reason) => {
                    if (!reason) {
                        Swal.showValidationMessage('You need to provide a reason')
                    }
                    return $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            reason: reason
                        }
                    })
                    .then(response => {
                        return response; // Success, pass response to result
                    })
                    .catch(error => {
                        Swal.showValidationMessage(
                            `Request failed: ${error}`
                        )
                    })
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Cancelled!',
                        text: 'Auction has been cancelled.',
                        icon: 'success'
                    }).then(() => {
                        location.reload(); // Reload to show updated status
                    });
                }
            })
        });

        // Force Delete Confirm
        $('.trigger-force-delete').click(function() {
            var url = $(this).data('url');
            Swal.fire({
                title: 'Are you sure?',
                text: "You will not be able to recover this auction!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, permanently delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "DELETE",
                        url: url,
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (data) {
                            Swal.fire(
                                'Deleted!',
                                'Auction has been permanently deleted.',
                                'success'
                            ).then(() => {
                                window.location.href = "{{ route('admin.auctions.index') }}";
                            });
                        },
                        error: function (data) {
                            Swal.fire(
                                'Error!',
                                'Something went wrong.',
                                'error'
                            )
                        }
                    });
                }
            })
        });
    });
</script>
@endpush
