@extends('website.layouts.dashboard')

@section('title', 'Notifications - LaraBids')

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-10">
        
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 pt-2">
            <div>
                <h1 class="h3 text-dark fw-bold mb-0">Notifications</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 small bg-transparent p-0">
                        <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}" class="text-decoration-none text-primary">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Notifications</li>
                    </ol>
                </nav>
            </div>
            
            @if($notifications->total() > 0)
                <div class="d-flex gap-2">
                    <form action="{{ route('user.notifications.read_all') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-white border shadow-sm btn-sm rounded-pill px-3 fw-bold text-primary hover-scale">
                            <i class="fas fa-check-double me-2"></i>Mark all read
                        </button>
                    </form>

                    <form action="{{ route('user.notifications.clear_all') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-white border shadow-sm btn-sm rounded-pill px-3 fw-bold text-danger hover-scale">
                            <i class="fas fa-trash-alt me-2"></i>Clear all
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <!-- Notifications List -->
        <div class="d-flex flex-column gap-3">
            @forelse($notifications as $notification)
                @php
                    $type = $notification->data['type'] ?? 'default';
                    $isRead = !is_null($notification->read_at);
                    
                    // Determine Icon and Color based on Type
                    $icon = 'bell';
                    $colorClass = 'primary';
                    
                    if(str_contains($type, 'auction')) {
                        $icon = 'gavel';
                        $colorClass = 'indigo'; // Custom color class or use primary
                    }
                    if(str_contains($type, 'bid')) {
                        $icon = 'hand-holding-usd';
                        $colorClass = 'success';
                    }
                    if(str_contains($type, 'outbid')) {
                        $icon = 'arrow-down';
                        $colorClass = 'warning';
                    }
                    if(str_contains($type, 'win')) {
                        $icon = 'trophy';
                        $colorClass = 'warning';
                    }
                    if(str_contains($type, 'payment')) {
                        $icon = 'credit-card';
                        $colorClass = 'info';
                    }
                    if(str_contains($type, 'cancelled') || str_contains($type, 'error')) {
                        $icon = 'exclamation-circle';
                        $colorClass = 'danger';
                    }
                @endphp

                <div class="card border-0 shadow-sm rounded-4 overflow-hidden transition-hover {{ $isRead ? 'bg-white opacity-75' : 'bg-white border-start border-4 border-' . $colorClass }}">
                    <div class="card-body p-4">
                        <div class="row align-items-center g-3">
                            
                            <!-- Icon Column -->
                            <div class="col-auto">
                                <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" 
                                     style="width: 50px; height: 50px; background-color: var(--bs-{{ $colorClass }}-bg-subtle, #f8f9fa); color: var(--bs-{{ $colorClass }}, #4e73df);">
                                    <i class="fas fa-{{ $icon }} fa-lg"></i>
                                </div>
                            </div>

                            <!-- Content Column -->
                            <div class="col">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <h6 class="mb-0 fw-bold text-dark">
                                        {{ $notification->data['title'] ?? 'New Notification' }}
                                        @if(!$isRead)
                                            <span class="badge bg-danger rounded-pill ms-2" style="font-size: 0.6rem; vertical-align: middle;">NEW</span>
                                        @endif
                                    </h6>
                                    <small class="text-muted fw-medium" style="font-size: 0.8rem;">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </small>
                                </div>
                                
                                <p class="text-muted mb-2 small text-truncate-2">{{ $notification->data['message'] ?? 'You have a new update.' }}</p>

                                @if(isset($notification->data['reason']))
                                    <div class="bg-light p-2 rounded-3 border mb-2">
                                        <small class="text-secondary d-block">
                                            <i class="fas fa-info-circle me-1"></i> {{ $notification->data['reason'] }}
                                        </small>
                                    </div>
                                @endif

                                @if(isset($notification->data['auction_id']))
                                    <a href="{{ route('auctions.show', $notification->data['auction_id']) }}" class="btn btn-link p-0 text-decoration-none small fw-bold text-primary">
                                        View Auction <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                @endif
                                
                                @if(isset($notification->data['action_url']) && !isset($notification->data['auction_id']))
                                     <a href="{{ $notification->data['action_url'] }}" class="btn btn-link p-0 text-decoration-none small fw-bold text-primary">
                                        View Details <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                @endif
                            </div>

                            <!-- Actions Column -->
                            <div class="col-auto border-start ps-3 d-flex flex-column gap-2 justify-content-center">
                                @if(!$isRead)
                                    <form action="{{ route('user.notifications.read', $notification->id) }}" method="POST" class="mark-read-form">
                                        @csrf
                                        <button type="submit" class="btn btn-light btn-sm rounded-circle text-success" data-bs-toggle="tooltip" title="Mark as Read" style="width: 32px; height: 32px;">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                @endif
                                
                                <form action="{{ route('user.notifications.destroy', $notification->id) }}" method="POST" class="delete-notification-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-light btn-sm rounded-circle text-muted hover-danger" data-bs-toggle="tooltip" title="Delete" style="width: 32px; height: 32px;">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <div class="mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle" style="width: 100px; height: 100px;">
                            <i class="far fa-bell fa-3x text-secondary opacity-25"></i>
                        </div>
                    </div>
                    <h4 class="fw-bold text-dark">No Notifications</h4>
                    <p class="text-muted text-center mx-auto" style="max-width: 300px;">
                        You're all caught up! We'll notify you when something important happens.
                    </p>
                    <a href="{{ route('auctions.index') }}" class="btn btn-primary rounded-pill px-4 mt-3 fw-bold shadow-sm">
                        Browse Auctions
                    </a>
                </div>
            @endforelse
        </div>

    @if($notifications->hasPages())
    <div class="d-flex justify-content-center mt-5">
        {{ $notifications->links() }}
    </div>
    @endif

</div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Clear All Confirmation
    const clearAllForm = document.querySelector('form[action="{{ route('user.notifications.clear_all') }}"]');
    if (clearAllForm) {
        clearAllForm.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this! All notifications will be deleted.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, clear all!',
                cancelButtonText: 'Cancel',
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-secondary ms-3'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    }

    // AJAX Delete Notification
    const deleteForms = document.querySelectorAll('.delete-notification-form');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            fetch(this.action, {
                method: 'POST',
                body: new FormData(this),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            }).then(res => res.json()).then(data => {
                if(data.success) {
                    const card = this.closest('.card');
                    card.style.transition = 'all 0.3s ease';
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.95)';
                    setTimeout(() => card.remove(), 300);
                }
            }).catch(err => console.error(err));
        });
    });

    // AJAX Mark as Read
    const markReadForms = document.querySelectorAll('.mark-read-form');
    markReadForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            fetch(this.action, {
                method: 'POST',
                body: new FormData(this),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            }).then(res => res.json()).then(data => {
                if(data.success) {
                    const card = this.closest('.card');
                    card.className = card.className.replace(/border-start border-4 border-[a-z]+/g, '');
                    card.classList.add('opacity-75'); 
                    
                    const badge = card.querySelector('.badge.bg-danger');
                    if(badge) badge.remove();
                    
                    this.remove(); // removes the form with the green button
                    
                    let headerBadge = document.querySelector('.fa-bell').nextElementSibling;
                    if(headerBadge && headerBadge.classList.contains('badge')) {
                        let count = parseInt(headerBadge.innerText.replace('+', ''));
                        if(!isNaN(count) && count > 0) {
                            count--;
                            if(count === 0) headerBadge.remove();
                            else headerBadge.innerText = count;
                        }
                    }
                }
            }).catch(err => console.error(err));
        });
    });

});
</script>
@endpush

<style>
.hover-scale:hover {
    transform: translateY(-2px);
    transition: transform 0.2s ease;
}
.transition-hover {
    transition: all 0.2s ease-in-out;
}
.transition-hover:hover {
    transform: translateY(-3px);
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.08) !important;
}
.hover-danger:hover {
    color: #dc3545 !important;
    background-color: #f8d7da !important;
}
.text-truncate-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

@endsection



