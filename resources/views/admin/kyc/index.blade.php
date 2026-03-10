@extends('admin.layouts.admin')

@section('title', 'KYC Verifications - LaraBids')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">KYC Verifications</h1>
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
            <h6 class="m-0 font-weight-bold text-primary">Pending & Recent KYC Submissions</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Full Name</th>
                            <th>ID Type</th>
                            <th>Submitted At</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kycs as $kyc)
                            <tr>
                                <td>{{ $kyc->user->username }}</td>
                                <td>{{ $kyc->full_name }}</td>
                                <td class="text-capitalize">{{ str_replace('_', ' ', $kyc->id_type) }}</td>
                                <td>{{ $kyc->created_at->format('M d, Y') }}</td>
                                <td>
                                    @if($kyc->status == 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif($kyc->status == 'approved')
                                        <span class="badge badge-success">Approved</span>
                                    @else
                                        <span class="badge badge-danger">Rejected</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.kyc.show', $kyc->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i> View Details
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No KYC submissions found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $kycs->links() }}
            </div>
        </div>
    </div>
@endsection
