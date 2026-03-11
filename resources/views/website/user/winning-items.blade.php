@extends('website.layouts.dashboard')

@section('title', 'Winning Items | LaraBids')

@section('content')

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4 pt-2">
    <div>
        <h1 class="h3 text-dark fw-bold mb-0">Won Items</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}" class="text-decoration-none text-primary">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Won Items</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Winning Items Table -->
<div class="card shadow-sm border-0 rounded-lg">
    <div class="card-header py-3 bg-white border-bottom d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 fw-bold text-dark"><i class="fas fa-list-ul me-2 text-primary"></i>Winning Items Directory</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive px-3 py-4">
            <table class="table table-hover border-bottom w-100" id="winningItemsTable" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-nowrap" style="min-width: 350px;">Item</th>
                        <th class="text-nowrap text-center">Winning Bid</th>
                        <th class="text-nowrap text-center">Won Date</th>
                        <th class="text-nowrap text-center">Status</th>
                        <th width="100" class="text-center text-nowrap">Action</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Data injected via DataTables --}}
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<style>
    /* Admin Matching Table Styling */
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

    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter {
        margin-bottom: 1.5rem;
        padding: 0 0.5rem;
    }
    .dataTables_wrapper .dataTables_filter input {
        border-radius: 2rem;
        padding: 0.5rem 1.5rem;
        border: 1px solid #e2e8f0;
        min-width: 250px;
        background: #f8fafc;
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    var table = $('#winningItemsTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: "{{ route('user.winning-items.data') }}",
            type: "GET",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        },
        columns: [
            { data: 'item', name: 'title' },
            { data: 'winning_bid', name: 'current_price' },
            { data: 'won_date', name: 'end_time' },
            { data: 'payment_status', name: 'payment_status', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        order: [[2, 'desc']], 
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search by item title...",
            lengthMenu: "Show _MENU_",
            paginate: {
                previous: '<i class="fas fa-chevron-left"></i>',
                next: '<i class="fas fa-chevron-right"></i>'
            },
            processing: '<div class="d-flex justify-content-center py-4"><div class="spinner-border text-primary" role="status"></div></div>'
        },
        dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        drawCallback: function() {
            $('.pagination').addClass('pagination-sm justify-content-end mb-0 ps-0');
            $('.page-item.active .page-link').css('border-radius', '0.5rem');
        }
    });
});
</script>
@endpush


@endsection
