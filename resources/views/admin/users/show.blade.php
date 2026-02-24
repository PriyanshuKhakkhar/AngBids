@extends('admin.layouts.admin')

@section('title', 'User Details - LaraBids')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">User Details</h1>
        <div>
            @if(request()->has('view_deleted') || $user->trashed())
               <form action="{{ route('admin.users.restore', $user->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-success shadow-sm">
                        <i class="fas fa-trash-restore fa-sm text-white-50 mr-1"></i> Restore User
                    </button>
                </form>
            @else
                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-primary shadow-sm">
                    <i class="fas fa-edit fa-sm text-white-50 mr-1"></i> Edit User
                </a>
            @endif
            
            <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50 mr-1"></i> Back to List
            </a>
        </div>
    </div>

    <div class="row">
        <!-- User Profile Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card shadow h-100 py-2 border-left-primary">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col-12 text-center">
                             <div class="mb-3">
                                <span class="fa-stack fa-3x">
                                    <i class="fas fa-circle fa-stack-2x text-primary"></i>
                                    <i class="fas fa-user fa-stack-1x fa-inverse"></i>
                                </span>
                             </div>
                            <h5 class="font-weight-bold text-gray-800">{{ $user->name }}</h5>
                            <p class="text-primary font-weight-bold mb-1">@_{{ $user->username }}</p>
                            <p class="text-muted mb-2 small">{{ $user->email }}</p>
                            <div class="mb-3">
                                @foreach($user->roles as $role)
                                    <span class="badge badge-info px-2 py-1">{{ ucfirst($role->name) }}</span>
                                @endforeach
                            </div>
                            
                            @if($user->trashed())
                                <div class="alert alert-danger py-2">
                                    This user is currently deleted.
                                </div>
                            @else
                                <div class="small">
                                    <span class="text-success"><i class="fas fa-circle"></i> Active Account</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Information Card -->
        <div class="col-xl-8 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Account Information</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tbody>
                                <tr class="border-bottom">
                                    <th width="35%" class="text-gray-600 pl-0">User ID</th>
                                    <td class="text-gray-800">#{{ $user->id }}</td>
                                </tr>
                                <tr class="border-bottom">
                                    <th class="text-gray-600 pl-0">Full Name</th>
                                    <td class="text-gray-800">{{ $user->name }}</td>
                                </tr>
                                <tr class="border-bottom">
                                    <th class="text-gray-600 pl-0">Username</th>
                                    <td class="text-gray-800">@_{{ $user->username }}</td>
                                </tr>
                                <tr class="border-bottom">
                                    <th class="text-gray-600 pl-0">Email Address</th>
                                    <td class="text-gray-800">{{ $user->email }}</td>
                                </tr>
                                <tr class="border-bottom">
                                    <th class="text-gray-600 pl-0">Roles</th>
                                    <td>
                                        @if($user->roles->isNotEmpty())
                                            @foreach($user->roles as $role)
                                                <span class="badge badge-secondary">{{ ucfirst($role->name) }}</span>
                                            @endforeach
                                        @else
                                            <span class="text-muted">No roles assigned</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr class="border-bottom">
                                    <th class="text-gray-600 pl-0">Joined Date</th>
                                    <td class="text-gray-800">{{ $user->created_at->format('F d, Y h:i A') }}</td>
                                </tr>
                                <tr class="border-bottom">
                                    <th class="text-gray-600 pl-0">Last Updated</th>
                                    <td class="text-gray-800">{{ $user->updated_at->format('F d, Y h:i A') }}</td>
                                </tr>
                                @if($user->trashed())
                                <tr class="bg-danger text-white">
                                    <th class="pl-2">Deleted At</th>
                                    <td>{{ $user->deleted_at->format('F d, Y h:i A') }}</td>
                                </tr>
                                <tr class="bg-danger text-white">
                                    <th class="pl-2">Deleted By</th>
                                    <td>{{ $user->deletedBy ? $user->deletedBy->name : 'Unknown' }}</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
