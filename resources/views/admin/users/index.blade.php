@extends('admin.layouts.admin')

@section('title', 'Manage Users - LaraBids')

@push('styles')
    <link href="{{ asset('admin-assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@section('content')
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Manage Users</h1>
    <p class="mb-4">View and manage all registered bidders and sellers in the system.</p>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">User Directory</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Joined Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Tiger Nixon</td>
                            <td>tiger@example.com</td>
                            <td><span class="badge badge-info">Seller</span></td>
                            <td>2023/10/12</td>
                            <td><span class="badge badge-success">Active</span></td>
                            <td>
                                <button class="btn btn-primary btn-sm">Edit</button>
                                <button class="btn btn-danger btn-sm">Block</button>
                            </td>
                        </tr>
                        <tr>
                            <td>Garrett Winters</td>
                            <td>garrett@example.com</td>
                            <td><span class="badge badge-primary">Bidder</span></td>
                            <td>2023/11/05</td>
                            <td><span class="badge badge-success">Active</span></td>
                            <td>
                                <button class="btn btn-primary btn-sm">Edit</button>
                                <button class="btn btn-danger btn-sm">Block</button>
                            </td>
                        </tr>
                        <tr>
                            <td>Ashton Cox</td>
                            <td>ashton@example.com</td>
                            <td><span class="badge badge-primary">Bidder</span></td>
                            <td>2023/09/20</td>
                            <td><span class="badge badge-warning">Blacklisted</span></td>
                            <td>
                                <button class="btn btn-primary btn-sm">Edit</button>
                                <button class="btn btn-success btn-sm">Unblock</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Page level plugins -->
    <script src="{{ asset('admin-assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin-assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <!-- Page level custom scripts -->
    <script>
        $(document).ready(function () {
            $('#dataTable').DataTable();
        });
    </script>
@endpush
