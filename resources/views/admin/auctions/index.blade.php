@extends('admin.layouts.admin')

@section('title', 'Manage Auctions - LaraBids')

@push('styles')
    <link href="{{ asset('admin-assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <style>
        .filter-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 0.4rem;
            color: #4a5568;
            font-weight: 700;
        }
        .filter-control {
            border-radius: 0.5rem;
            border: 1px solid #e2e8f0;
            padding: 0.55rem 1rem;
            font-size: 0.9rem;
            color: #4a5568;
            background-color: #f8fafc;
            transition: all 0.2s ease-in-out;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.025);
        }
        .filter-control:focus {
            border-color: #a3bffa;
            background-color: #fff;
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.15);
            outline: none;
        }
        .table th {
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            color: #64748b;
            font-weight: 700;
            border-bottom-width: 2px !important;
            background-color: #f8fafc;
        }
        .table td {
            vertical-align: middle;
            color: #475569;
            font-size: 0.9rem;
        }
        .auction-img-thumbnail {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 0.35rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
        }
        .btn-action {
            width: 32px;
            height: 32px;
            padding: 0;
            line-height: 32px;
            text-align: center;
            border-radius: 0.35rem;
            display: inline-block;
            transition: all 0.2s;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .dataTables_wrapper .dataTables_filter input {
            border-radius: 0.5rem;
            border: 1px solid #e2e8f0;
            padding: 0.4rem 0.75rem;
        }
        .dataTables_wrapper .dataTables_filter input:focus {
            outline: none;
            border-color: #a3bffa;
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.15);
        }
        .btn-reset-filter {
            border-radius: 0.5rem;
            border: 1px solid #e2e8f0;
            background-color: #fff;
            color: #4a5568;
            font-weight: 600;
            transition: all 0.2s ease;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        .btn-reset-filter:hover {
            background-color: #f1f5f9;
            color: #1a202c;
            border-color: #cbd5e0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
    </style>
@endpush

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 font-weight-bold">
                <i class="fas fa-gavel text-primary mr-2"></i>Manage Auctions
            </h1>
            <p class="text-muted small mt-1 mb-0">Monitor, approve, and manage all marketplace listings.</p>
        </div>
        <div class="d-none d-sm-inline-block shadow-sm px-4 py-2 bg-white rounded-pill border">
            <span class="text-xs font-weight-bold text-uppercase text-muted mr-2">Total Auctions:</span>
            <span class="h5 mb-0 font-weight-bold text-primary">{{ number_format($total_auctions) }}</span>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="card shadow-sm border-0 mb-4 rounded-lg" style="border-left: 4px solid #4e73df !important;">
        <div class="card-body p-4">
            <div class="row align-items-end">
                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 mb-3">
                    <label for="categoryFilter" class="filter-label"><i class="fas fa-tags mr-1"></i> Category</label>
                    <select id="categoryFilter" class="custom-select filter-control w-100">
                        <option value="" selected>All Categories</option>
                        @foreach($categories as $cat)
                            <optgroup label="{{ $cat->name }}">
                                <option value="{{ $cat->slug }}">{{ $cat->name }} (All)</option>
                                @foreach($cat->children as $child)
                                    <option value="{{ $child->slug }}">&nbsp;&nbsp;&mdash; {{ $child->name }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>

                <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 mb-3">
                    <label for="statusFilter" class="filter-label"><i class="fas fa-circle-notch mr-1"></i> Status</label>
                    <select id="statusFilter" class="custom-select filter-control w-100">
                        <option value="all" selected>All Statuses</option>
                        <option value="pending">Pending</option>
                        <option value="active">Active/Live</option>
                        <option value="closed">Closed / Past</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>

                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
                    <label for="sortFilter" class="filter-label"><i class="fas fa-sort-amount-down-alt mr-1"></i> Sort By</label>
                    <select id="sortFilter" class="custom-select filter-control w-100">
                        <option value="latest" selected>Latest</option>
                        <option value="ending_soon">Ending Soon</option>
                        <option value="price_desc">High Price</option>
                        <option value="price_asc">Low Price</option>
                    </select>
                </div>
                
                <div class="col-xl-3 col-lg-2 col-md-6 col-sm-6 mb-3">
                    <label for="dateFilter" class="filter-label"><i class="far fa-calendar-alt mr-1"></i> Added Date</label>
                    <input type="date" id="dateFilter" class="form-control filter-control w-100">
                </div>
                <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 mb-3">
                    <button type="button" class="btn-reset-filter w-100" id="resetFilters" style="height: calc(1.5em + .75rem + 2px);">
                        <i class="fas fa-sync-alt mr-2 text-primary"></i> Reset Search
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Directory Card -->
    <div class="card shadow-sm border-0 rounded-lg">
        <div class="card-header py-3 bg-white border-bottom d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-dark"><i class="fas fa-list-ul mr-2 text-primary"></i>Auction Directory</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive px-3 py-4">
                <table class="table table-hover border-bottom" id="auctions-table" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="30">Id</th>
                            <th width="60" class="text-center">Image</th>
                            <th>Title & Details</th>
                            <th>Category</th>
                            <th>Seller</th>
                            <th class="text-right">Current Bid</th>
                            <th class="text-center">Status</th>
                            <th>End Time</th>
                            <th width="150" class="text-center text-nowrap">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('admin-assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin-assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            // Setup CSRF token for AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var currentStatus = 'all';
            var currentCategory = '';
            var currentSort = 'latest';
            var currentDate = '';

            var table = $('#auctions-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.auctions.index') }}",
                    data: function (d) {
                        d.status = currentStatus;
                        d.category = currentCategory;
                        d.sort = currentSort;
                        d.date = currentDate;
                    }
                },
                language: {
                    searchPlaceholder: "Search records...",
                    lengthMenu: "_MENU_ entries per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ auctions"
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-muted text-center'},
                    {data: 'image', name: 'image', orderable: false, searchable: false, className: 'text-center'},
                    {data: 'title', name: 'title', className: 'font-weight-bold text-dark'},
                    {data: 'category', name: 'category.name'},
                    {data: 'user', name: 'user.name'},
                    {data: 'current_price', name: 'current_price', className: 'text-right'},
                    {data: 'status', name: 'status', className: 'text-center'},
                    {data: 'end_time', name: 'end_time', className: 'text-muted small'},
                    {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center text-nowrap'},
                ],
                drawCallback: function() {
                    $('.btn-info').removeClass('btn-info').addClass('btn-outline-info').html('<i class="fas fa-eye"></i>');
                    $('.btn-success:not(.restore-auction)').removeClass('btn-success').addClass('btn-outline-success').html('<i class="fas fa-check"></i>');
                    $('.btn-danger:not(.force-delete-auction)').removeClass('btn-danger').addClass('btn-outline-danger').html('<i class="fas fa-trash"></i>');
                    $('.restore-auction').removeClass('btn-success').addClass('btn-outline-success').html('<i class="fas fa-trash-restore"></i>');
                    $('.force-delete-auction').removeClass('btn-danger').addClass('btn-outline-danger').html('<i class="fas fa-times"></i>');
                    
                    $('.btn-sm').addClass('btn-action mx-1');
                    
                    $('#auctions-table img').addClass('auction-img-thumbnail');
                }
            });

            // Filters Change Handler
            $('#statusFilter, #categoryFilter, #sortFilter, #dateFilter').on('change', function() {
                currentStatus = $('#statusFilter').val();
                currentCategory = $('#categoryFilter').val();
                currentSort = $('#sortFilter').val();
                currentDate = $('#dateFilter').val();
                table.draw();
            });

            // Reset Filters
            $('#resetFilters').on('click', function() {
                $('#statusFilter').val('all');
                $('#categoryFilter').val('');
                $('#sortFilter').val('latest');
                $('#dateFilter').val('');
                
                currentStatus = 'all';
                currentCategory = '';
                currentSort = 'latest';
                currentDate = '';
                
                // Clear datatables search
                table.search('').draw();
            });

            // Delete Auction
            $('body').on('click', '.delete-auction', function () {
                var url = $(this).data('url');
                Swal.fire({
                    title: 'Move to Trash?',
                    text: "You can restore this auction later.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e74a3b',
                    cancelButtonColor: '#858796',
                    confirmButtonText: 'Yes, trash it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "DELETE",
                            url: url,
                            success: function (data) {
                                table.draw();
                                Swal.fire('Trashed!', 'Auction moved to trash.', 'success');
                            },
                            error: function () {
                                Swal.fire('Error!', 'Something went wrong.', 'error');
                            }
                        });
                    }
                })
            });

            // Restore Auction
            $('body').on('click', '.restore-auction', function () {
                var url = $(this).data('url');
                $.ajax({
                    type: "POST",
                    url: url,
                    success: function (data) {
                        table.draw();
                        Swal.fire('Restored!', 'Auction has been restored.', 'success');
                    },
                    error: function () {
                        Swal.fire('Error!', 'Something went wrong.', 'error');
                    }
                });
            });

            // Force Delete Auction
            $('body').on('click', '.force-delete-auction', function () {
                var url = $(this).data('url');
                Swal.fire({
                    title: 'Permanent Delete!',
                    text: "You will not be able to recover this auction!",
                    icon: 'error',
                    showCancelButton: true,
                    confirmButtonColor: '#5a5c69',
                    cancelButtonColor: '#858796',
                    confirmButtonText: 'Permanently Delete'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "DELETE",
                            url: url,
                            success: function (data) {
                                table.draw();
                                Swal.fire('Deleted!', 'Auction permanently deleted.', 'success');
                            },
                            error: function () {
                                Swal.fire('Error!', 'Something went wrong.', 'error');
                            }
                        });
                    }
                })
            });
        });
    </script>
@endpush
