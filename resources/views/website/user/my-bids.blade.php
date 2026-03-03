@extends('website.layouts.dashboard')

@section('title', 'My Bids | LaraBids')

@section('content')

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4 pt-2">
    <div>
        <h1 class="h3 text-dark fw-bold mb-0">My Active Bids</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}" class="text-decoration-none text-primary">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">My Bids</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Bids Table -->
<div class="card card-elite border-0 shadow-sm overflow-hidden" style="border-radius: 1.25rem;">
    <div class="card-body p-0">
        <div class="table-responsive p-4">
            <table class="table table-hover align-middle mb-0 w-100" id="myBidsTable" style="border-collapse: separate; border-spacing: 0 12px;">
                <thead>
                    <tr class="text-muted small text-uppercase fw-bold opacity-75">
                        <th class="border-0 ps-3">Auction Item</th>
                        <th class="border-0">My Bid</th>
                        <th class="border-0">Current Price</th>
                        <th class="border-0">Status</th>
                        <th class="border-0">Time Left</th>
                        <th class="border-0 text-end pe-3">Action</th>
                    </tr>
                </thead>
                <tbody class="border-0">
                    {{-- Data injected via DataTables --}}
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<style>
    /* Premium Table Styling */
    #myBidsTable tbody tr {
        background-color: #fff;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        transition: all 0.2s ease;
        border-radius: 1rem;
    }
    #myBidsTable tbody tr:hover {
        background-color: #fbfcfe;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    #myBidsTable td {
        padding: 1.25rem 0.75rem;
        border-top: 1px solid #f1f5f9 !important;
        border-bottom: 1px solid #f1f5f9 !important;
    }
    #myBidsTable td:first-child {
        border-left: 1px solid #f1f5f9 !important;
        border-top-left-radius: 1rem;
        border-bottom-left-radius: 1rem;
        padding-left: 1.5rem;
    }
    #myBidsTable td:last-child {
        border-right: 1px solid #f1f5f9 !important;
        border-top-right-radius: 1rem;
        border-bottom-right-radius: 1rem;
        padding-right: 1.5rem;
    }
    
    .btn-icon-elite {
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        padding: 0;
    }
    .btn-icon-elite:hover { transform: scale(1.1); }

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
    var table = $('#myBidsTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: "{{ route('user.my-bids.data') }}",
            type: "GET",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        },
        columns: [
            { data: 'item', name: 'item', orderable: false },
            { data: 'my_bid', name: 'amount' },
            { data: 'current_price', name: 'auction.current_price' },
            { data: 'status', name: 'status', orderable: false, searchable: false },
            { data: 'time_left', name: 'time_left', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        order: [[1, 'desc']], 
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search by item or price...",
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
