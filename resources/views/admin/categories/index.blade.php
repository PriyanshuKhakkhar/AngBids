@extends('admin.layouts.admin')

@section('title', 'Categories - LaraBids')

@push('styles')
    <link href="{{ asset('admin-assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Auction Categories</h1>
        <button class="btn btn-primary shadow-sm btn-sm" data-toggle="modal"
            data-target="#addCategoryModal"><i class="fas fa-plus fa-sm text-white-50"></i>
            Add New Category</button>
    </div>

    <div class="row">
        <!-- Category Stats -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total
                                Categories</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">12</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-tags fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Categories List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Icon</th>
                            <th>Category Name</th>
                            <th>Total Items</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><i class="fas fa-laptop text-primary"></i></td>
                            <td class="font-weight-bold">Electronics</td>
                            <td>450</td>
                            <td><span class="badge badge-success">Active</span></td>
                            <td>
                                <button class="btn btn-sm btn-circle btn-info mr-1" title="Edit"><i
                                        class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-circle btn-danger" title="Delete"><i
                                        class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-clock text-info"></i></td>
                            <td class="font-weight-bold">Watches</td>
                            <td>120</td>
                            <td><span class="badge badge-success">Active</span></td>
                            <td>
                                <button class="btn btn-sm btn-circle btn-info mr-1" title="Edit"><i
                                        class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-circle btn-danger" title="Delete"><i
                                        class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-car text-warning"></i></td>
                            <td class="font-weight-bold">Vintage Cars</td>
                            <td>15</td>
                            <td><span class="badge badge-secondary">Inactive</span></td>
                            <td>
                                <button class="btn btn-sm btn-circle btn-info mr-1" title="Edit"><i
                                        class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-circle btn-danger" title="Delete"><i
                                        class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-gem text-danger"></i></td>
                            <td class="font-weight-bold">Jewelry</td>
                            <td>85</td>
                            <td><span class="badge badge-success">Active</span></td>
                            <td>
                                <button class="btn btn-sm btn-circle btn-info mr-1" title="Edit"><i
                                        class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-circle btn-danger" title="Delete"><i
                                        class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addCategoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryModalLabel">Add New Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="categoryName">Category Name</label>
                            <input type="text" class="form-control" id="categoryName" placeholder="Enter category name">
                        </div>
                        <div class="form-group">
                            <label for="categoryIcon">Icon Class (Font Awesome)</label>
                            <input type="text" class="form-control" id="categoryIcon" placeholder="e.g. fas fa-laptop">
                            <small class="form-text text-muted">Use Font Awesome classes (e.g. 'fas fa-car')</small>
                        </div>
                        <div class="form-group">
                            <label for="categoryStatus">Status</label>
                            <select class="form-control" id="categoryStatus">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save Category</button>
                </div>
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
