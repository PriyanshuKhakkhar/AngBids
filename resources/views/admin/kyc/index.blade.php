@extends('admin.layouts.admin')

@section('title', 'KYC Verifications - LaraBids')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 font-weight-bold">
                <i class="fas fa-id-card text-primary mr-2"></i>KYC Verifications
            </h1>
            <p class="text-muted small mt-1 mb-0">Review and manage user identity verification requests.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-lg">
        <div class="card-header py-3 bg-white border-bottom d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-dark"><i class="fas fa-list-ul mr-2 text-primary"></i>KYC Submissions Monitoring</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive px-3 py-4">
                <table class="table table-hover border-bottom" id="kyc-table" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="50" class="text-center text-nowrap">Id</th>
                            <th>User</th>
                            <th>Full Name</th>
                            <th>ID Type</th>
                            <th>Submitted At</th>
                            <th class="text-center">Status</th>
                            <th width="100" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link href="{{ asset('admin-assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <style>
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
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('admin-assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin-assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#kyc-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.kyc.data') }}",
                language: {
                    searchPlaceholder: "Search records...",
                    lengthMenu: "Entries per page: _MENU_",
                    info: "Showing _START_ to _END_ of _TOTAL_ submissions"
                },
                dom: "<'row mb-3'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>rt<'row mt-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-muted text-center'},
                    { data: 'user', name: 'user' },
                    { data: 'full_name', name: 'full_name', className: 'font-weight-bold text-dark' },
                    { data: 'id_type', name: 'id_type' },
                    { data: 'created_at', name: 'created_at', className: 'text-muted small' },
                    { data: 'status', name: 'status', className: 'text-center' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center text-nowrap' }
                ],
                order: [[4, 'desc']], // Sort by "Submitted At" (index 4) by default
                pageLength: 10,
                drawCallback: function() {
                    $('.btn-info').removeClass('btn-info').addClass('btn-outline-info').html('<i class="fas fa-eye"></i>');
                    $('.btn-sm').addClass('btn-action mx-1');
                }
            });
        });
    </script>
@endpush
