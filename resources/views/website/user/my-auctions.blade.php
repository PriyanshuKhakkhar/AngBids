@extends('website.layouts.dashboard')

@section('title', 'My Auctions | LaraBids')

@section('content')

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4 pt-2">
    <div>
        <h1 class="h3 text-dark fw-bold mb-0">My Auctions</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}" class="text-decoration-none text-primary">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">My Auctions</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('auctions.create') }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
        <i class="fas fa-plus-circle me-1"></i> List New Item
    </a>
</div>

<!-- Auctions List -->
<div class="card shadow-sm border-0 rounded-lg">
    <div class="card-header py-3 bg-white border-bottom d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 fw-bold text-dark"><i class="fas fa-list-ul me-2 text-primary"></i>My Auctions Directory</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive px-3 py-4">
            <table class="table table-hover border-bottom w-100" id="myAuctionsTable" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-nowrap" style="min-width: 350px;">Item Details</th>
                        <th class="text-nowrap text-center">Status</th>
                        <th class="text-nowrap">Price</th>
                        <th class="text-nowrap">Winner</th>
                        <th class="text-center text-nowrap">Stats</th>
                        <th width="100" class="text-center text-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Data injected via DataTables --}}
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Hidden Delete Form --}}
<form id="deleteAuctionForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

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
    /* DataTable Overrides */
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
    .dataTables_wrapper .dataTables_filter input:focus {
        background: #fff;
        border-color: var(--bs-primary);
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
        outline: none;
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    if ($.fn.DataTable.isDataTable('#myAuctionsTable')) {
        $('#myAuctionsTable').DataTable().destroy();
    }

    var table = $('#myAuctionsTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        autoWidth: false,
        ajax: {
            url: "{{ route('user.my-auctions.data') }}",
            type: "GET",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            error: function (xhr, error, code) {
                console.error('DataTables Error:', xhr.responseText);
                if(xhr.status == 500) {
                    alert('Server Error: Check controller logic.');
                }
            }
        },
        columns: [
            { data: 'item', name: 'title' },
            { data: 'status', name: 'status', orderable: true },
            { data: 'price', name: 'current_price' },
            { data: 'winner', name: 'winner', orderable: false, searchable: false },
            { data: 'bids', name: 'bids', orderable: false, searchable: false, className: 'text-center' },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-end' }
        ],
        order: [[2, 'desc']], 
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search by title, price or status...",
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

    window.confirmDelete = function(id) {
        Swal.fire({
            title: 'Delete Auction?',
            text: "This action cannot be undone. All bids will be lost!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            customClass: {
                confirmButton: 'btn btn-danger',
                cancelButton: 'btn btn-secondary ms-3'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                let form = document.getElementById('deleteAuctionForm');
                form.action = "{{ url('auctions') }}/" + id;
                form.submit();
            }
        });
    }
});
</script>
@endpush

@endsection
