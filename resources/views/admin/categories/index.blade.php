@extends('admin.layouts.admin')

@section('title', 'Categories - LaraBids')

@push('styles')
    <link href="{{ asset('admin-assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <style>
        #categories-table th {
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            color: #64748b;
            font-weight: 700;
            border-top: none;
            padding-top: 1.25rem;
            padding-bottom: 1.25rem;
        }
        #categories-table td {
            vertical-align: middle;
            font-size: 0.9rem;
        }
        .btn-action {
            width: 32px;
            height: 32px;
            padding: 0;
            line-height: 32px;
            text-align: center;
            border-radius: 0.35rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .category-icon-box {
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.5rem;
            background-color: #eef2ff;
            color: #4e73df;
            margin: 0 auto;
            font-size: 1rem;
        }
    </style>
@endpush

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Auction Categories</h1>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary shadow-sm btn-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Add New Category
        </a>
    </div>

    <!-- Stats Row -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Categories</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $total_categories }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tags fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Categories List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="categories-table" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="40">#</th>
                            <th width="60" class="text-center">Icon</th>
                            <th>Category Name</th>
                            <th>Parent</th>
                            <th>Slug</th>
                            <th>Count</th>
                            <th width="130" class="text-center">Actions</th>
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

            var table = $('#categories-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.categories.index') }}",
                language: {
                    searchPlaceholder: "Search Name, Slug...",
                    lengthMenu: "_MENU_ entries per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ categories"
                },
                columns: [
                    {data: 'DT_RowIndex',  name: 'DT_RowIndex',  orderable: false, searchable: false, className: 'text-muted text-center'},
                    {data: 'icon',         name: 'icon',         orderable: false, searchable: false, className: 'text-center'},
                    {data: 'name',         name: 'name',         className: 'font-weight-bold text-dark'},
                    {data: 'parent',       name: 'parent',       orderable: false},
                    {data: 'slug',         name: 'slug',         className: 'text-muted small'},
                    {data: 'count',        name: 'auctions_count', searchable: false},
                    {data: 'action',       name: 'action',       orderable: false, searchable: false, className: 'text-center text-nowrap'},
                ],
                drawCallback: function () {
                    // Consistent Edit icon (btn-primary)
                    $('#categories-table .btn-primary:not(.btn-action-done)')
                        .addClass('btn-outline-primary btn-action btn-action-done mx-1')
                        .removeClass('btn-primary btn-circle');

                    // Consistent Delete icon (soft delete)
                    $('#categories-table .delete-category')
                        .addClass('btn-outline-danger btn-action mx-1')
                        .removeClass('btn-danger btn-circle');

                    // Consistent Restore icon
                    $('#categories-table .restore-category')
                        .addClass('btn-outline-success btn-action mx-1')
                        .removeClass('btn-success btn-circle');

                    // Consistent Force Delete icon
                    $('#categories-table .force-delete-category')
                        .addClass('btn-outline-danger btn-action mx-1')
                        .removeClass('btn-danger btn-circle');

                    // Wrap category icons in styled box
                    $('#categories-table tbody td:nth-child(2)').each(function () {
                        var $td = $(this);
                        if ($td.find('.category-icon-box').length === 0) {
                            $td.html('<div class="category-icon-box">' + $td.html() + '</div>');
                        }
                    });
                }
            });

            // Delete Category (soft)
            $('body').on('click', '.delete-category', function () {
                var url = $(this).data('url');
                Swal.fire({
                    title: 'Move to Trash?',
                    text: "You can restore this category later.",
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
                            success: function () {
                                table.draw();
                                Swal.fire('Trashed!', 'Category has been moved to trash.', 'success');
                            },
                            error: function () {
                                Swal.fire('Error!', 'Something went wrong.', 'error');
                            }
                        });
                    }
                });
            });

            // Restore Category
            $('body').on('click', '.restore-category', function () {
                var url = $(this).data('url');
                $.ajax({
                    type: "POST",
                    url: url,
                    success: function () {
                        table.draw();
                        Swal.fire('Restored!', 'Category has been restored successfully.', 'success');
                    },
                    error: function () {
                        Swal.fire('Error!', 'Something went wrong.', 'error');
                    }
                });
            });

            // Force Delete Category
            $('body').on('click', '.force-delete-category', function () {
                var url = $(this).data('url');
                Swal.fire({
                    title: 'Permanent Delete!',
                    text: "This category will be permanently deleted and cannot be recovered!",
                    icon: 'error',
                    showCancelButton: true,
                    confirmButtonColor: '#e74a3b',
                    cancelButtonColor: '#858796',
                    confirmButtonText: 'Yes, delete permanently!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "DELETE",
                            url: url,
                            success: function () {
                                table.draw();
                                Swal.fire('Deleted!', 'Category has been permanently deleted.', 'success');
                            },
                            error: function (xhr) {
                                var errorMsg = 'Something went wrong.';
                                if (xhr.responseJSON && xhr.responseJSON.error) {
                                    errorMsg = xhr.responseJSON.error;
                                }
                                Swal.fire('Error!', errorMsg, 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
