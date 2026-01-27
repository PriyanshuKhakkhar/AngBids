@extends('admin.layouts.admin')

@section('title', 'Manage Auctions - LaraBids')

@push('styles')
    <link href="{{ asset('admin-assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manage Auctions</h1>
    </div>



    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Auctions ({{ $total_auctions }})</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="auctions-table" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="30">#</th>
                            <th>Title</th>
                            <th>User</th>
                            <th>Current Price</th>
                            <th>Status</th>
                            <th>End Time</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
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
            // Setup CSRF token for AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var table = $('#auctions-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.auctions.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'title', name: 'title'},
                    {data: 'user', name: 'user.name'}, // Ensure this matches controller
                    {data: 'current_price', name: 'current_price'},
                    {data: 'status', name: 'status'},
                    {data: 'end_time', name: 'end_time'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });

            // Cancel Auction
            $('body').on('click', '.cancel-auction', function () {
                var url = $(this).data('url');
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
                        $.ajax({
                            type: "POST",
                            url: url,
                            success: function (data) {
                                table.draw();
                                Swal.fire(
                                    'Cancelled!',
                                    'Auction has been cancelled.',
                                    'success'
                                )
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

            // Delete Auction
            $('body').on('click', '.delete-auction', function () {
                var url = $(this).data('url');
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
                        $.ajax({
                            type: "DELETE",
                            url: url,
                            success: function (data) {
                                table.draw();
                                Swal.fire(
                                    'Deleted!',
                                    'Auction has been deleted.',
                                    'success'
                                )
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
