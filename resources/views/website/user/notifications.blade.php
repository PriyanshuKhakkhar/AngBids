@extends('website.layouts.dashboard')

@section('title', 'Notifications | LaraBids')

@section('content')

<!-- Notifications Header -->
<div class="d-flex justify-content-between align-items-center mb-5">
    <div>
        <span class="text-primary fw-bold small text-uppercase mb-1 d-block">Notifications</span>
        <h1 class="h3 text-dark fw-bold mb-0">All Alerts</h1>
    </div>
    @if($notifications->total() > 0)
        <div class="d-flex gap-2">
            <form action="{{ route('user.notifications.read_all') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                    <i class="fas fa-check-double me-1"></i> Mark All as Read
                </button>
            </form>
            <form action="{{ route('user.notifications.clear_all') }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3" onclick="return confirm('Are you sure you want to clear all notifications?')">
                    <i class="fas fa-trash-alt me-1"></i> Clear All
                </button>
            </form>
        </div>
    @endif
</div>

    <!-- Notifications List -->
    <div>
        @forelse($notifications as $notification)
            <div class="card mb-3 shadow-sm border-0 overflow-hidden" style="border-radius: 10px;">
                <div class="card-body p-3 {{ $notification->read_at ? 'bg-white' : 'bg-primary bg-opacity-5' }}">
                    <div class="d-flex w-100 align-items-center justify-content-between">
                        <div class="d-flex align-items-center flex-grow-1">
                            <!-- Icon -->
                            <div class="me-3">
                                <div class="bg-{{ isset($notification->data['type']) && $notification->data['type'] === 'auction_cancelled' ? 'danger' : 'primary' }} text-white rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 36px; height: 36px;">
                                    <i class="fas fa-{{ isset($notification->data['type']) && $notification->data['type'] === 'auction_cancelled' ? 'ban' : 'bell' }}"></i>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.95rem;">
                                        {{ $notification->data['title'] ?? 'Notification' }}
                                        @if(!$notification->read_at)
                                            <span class="badge bg-primary rounded-pill badge-sm ms-2" style="font-size: 0.6em;">NEW</span>
                                        @endif
                                    </h6>
                                    <small class="text-muted" style="font-size: 0.75rem;">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $notification->created_at->diffForHumans() }}
                                    </small>
                                </div>
                                <p class="mb-0 text-secondary small" style="line-height: 1.4;">{{ $notification->data['message'] ?? '' }}</p>
                                @if(isset($notification->data['reason']) && $notification->data['reason'])
                                    <div class="mt-2 p-2 bg-light rounded border border-light small text-muted">
                                        <strong>Reason:</strong> {{ $notification->data['reason'] }}
                                    </div>
                                @endif
                                
                                <!-- View Auction Link (if applicable) -->
                                @if(isset($notification->data['auction_id']))
                                    <div class="mt-2">
                                        <a href="{{ route('auctions.show', $notification->data['auction_id']) }}" 
                                           class="text-decoration-none small fw-bold text-primary">
                                            View Auction <i class="fas fa-arrow-right ms-1"></i>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="ms-3 d-flex flex-column gap-2">
                            @if(!$notification->read_at)
                                <form action="{{ route('user.notifications.read', $notification->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="stay" value="1">
                                    <button type="submit" class="btn btn-sm btn-light text-success border-0 rounded-circle" style="width: 32px; height: 32px;" title="Mark as Read">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                            @endif
                            
                            <form action="{{ route('user.notifications.destroy', $notification->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light text-secondary border-0 rounded-circle" style="width: 32px; height: 32px;" title="Clear" 
                                        onclick="return confirm('Are you sure you want to clear this notification?')">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="card shadow-sm border-0 p-5 text-center">
                <div class="mb-3">
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="fas fa-bell-slash fa-2x text-secondary opacity-50"></i>
                    </div>
                </div>
                <h5 class="text-dark fw-bold">No notifications yet</h5>
                <p class="text-muted mb-4">You're all caught up! Check back later for updates.</p>
                <div>
                    <a href="{{ route('user.dashboard') }}" class="btn btn-outline-primary rounded-pill px-4">
                        Go to Dashboard
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($notifications->hasPages())
        <div class="card-footer bg-white border-light py-3">
            <div class="d-flex justify-content-center">
                {{ $notifications->links() }}
            </div>
        </div>
    @endif
</div>

@endsection
