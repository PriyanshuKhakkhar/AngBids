@extends('admin.layouts.admin')

@section('title', 'Manage Auctions - LaraBids')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manage Auctions</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Auctions</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>User</th>
                            <th>Current Price</th>
                            <th>Status</th>
                            <th>End Time</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($auctions as $auction)
                            <tr>
                                <td>{{ $auction->id }}</td>
                                <td>{{ $auction->title }}</td>
                                <td>{{ $auction->user->name }}</td>
                                <td>${{ number_format($auction->current_price, 2) }}</td>
                                <td>
                                    <span class="badge badge-{{ $auction->status == 'active' ? 'success' : ($auction->status == 'closed' ? 'secondary' : ($auction->status == 'cancelled' ? 'danger' : 'warning')) }}">
                                        {{ ucfirst($auction->status) }}
                                    </span>
                                </td>
                                <td>{{ $auction->end_time->format('M d, Y H:i') }}</td>
                                <td>
                                    <div class="d-flex">
                                        <a href="{{ route('admin.auctions.show', $auction) }}" class="btn btn-info btn-sm mr-1">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($auction->status != 'cancelled' && $auction->status != 'closed')
                                            <form action="{{ route('admin.auctions.cancel', $auction) }}" method="POST" class="mr-1">
                                                @csrf
                                                <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('Are you sure you want to cancel this auction?')">
                                                    <i class="fas fa-ban"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.auctions.destroy', $auction) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this auction? This action cannot be undone.')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No auctions found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $auctions->links() }}
            </div>
        </div>
    </div>
@endsection
