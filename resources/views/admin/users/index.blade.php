@extends('admin.layouts.admin')

@section('title', 'Manage Users - LaraBids')

@push('styles')
<link href="{{ asset('admin-assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Manage Users</h1>
    <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50"></i> Add New User
    </a>
</div>

{{-- Alerts --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
@endif

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">User Directory</h6>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Joined Date</th>
                    <th width="180">Actions</th>
                </tr>
                </thead>
                <tbody>

                @foreach($users as $user)
                    <tr class="{{ $user->trashed() ? 'bg-gray-100' : '' }}">
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>

                        <td>
                            @foreach($user->roles as $role)
                                <span class="badge badge-info">{{ ucfirst($role->name) }}</span>
                            @endforeach
                        </td>

                        <td>
                            @if($user->trashed())
                                <span class="badge badge-danger">Deleted</span>
                            @else
                                <span class="badge badge-success">Active</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('M d, Y') }}</td>

                        <td>
                            {{-- 👁️ VIEW (redirects to EDIT page) --}}
                            <a href="{{ route('admin.users.show', $user->id) }}"
                               class="btn btn-info btn-sm" title="View">
                                <i class="fas fa-eye"></i>
                            </a>

                            {{-- ✏️ EDIT --}}
                            @if(!$user->trashed())
                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                   class="btn btn-primary btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                            @endif

                            {{-- ♻️ RESTORE --}}
                            @if($user->trashed())
                                <form action="{{ route('admin.users.restore', $user->id) }}"
                                      method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm" title="Restore">
                                        <i class="fas fa-trash-restore"></i>
                                    </button>
                                </form>
                            @endif

                            {{-- 🗑️ DELETE / FORCE DELETE --}}
                            @if(auth()->id() !== $user->id)
                                @if($user->trashed())
                                    <form action="{{ route('admin.users.force_delete', $user->id) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Permanently delete this user?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Permanent Delete">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.users.destroy', $user->id) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Delete this user?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            @else
                                <button class="btn btn-secondary btn-sm" disabled title="You">
                                    <i class="fas fa-user-lock"></i>
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('admin-assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('admin-assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

<script>
    $(function () {
        $('#dataTable').DataTable();

        // 🚫 Prevent row-click GET redirect
        $('#dataTable tbody').on('click', 'tr', function (e) {
            if (!$(e.target).is('a, button, i, form')) {
                e.preventDefault();
            }
        });
    });
</script>
@endpush
