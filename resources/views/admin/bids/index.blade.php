@extends('admin.layouts.admin')

@section('title', 'Bids History - LaraBids')

@push('styles')
    <link href="{{ asset('admin-assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@section('content')
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Bids History</h1>
    <p class="mb-4">Real-time log of all bids placed across all active auctions.</p>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Live Bid Feed</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Bid ID</th>
                            <th>Bidder</th>
                            <th>Auction Item</th>
                            <th>Amount</th>
                            <th>Time</th>
                            <th>Auto-Bid</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#B-9021</td>
                            <td>Tiger Nixon</td>
                            <td>Vintage Rolex Submariner</td>
                            <td>$12,500</td>
                            <td>2024/01/21 14:05:32</td>
                            <td>No</td>
                            <td><span class="badge badge-success">Leading</span></td>
                        </tr>
                        <tr>
                            <td>#B-9020</td>
                            <td>Garrett Winters</td>
                            <td>Vintage Rolex Submariner</td>
                            <td>$12,400</td>
                            <td>2024/01/21 14:02:10</td>
                            <td>Yes</td>
                            <td><span class="badge badge-warning">Outbid</span></td>
                        </tr>
                        <tr>
                            <td>#B-9019</td>
                            <td>Ashton Cox</td>
                            <td>1964 Ferrari GTO Model</td>
                            <td>$2,400</td>
                            <td>2024/01/21 13:58:15</td>
                            <td>No</td>
                            <td><span class="badge badge-success">Leading</span></td>
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
            $('#dataTable').DataTable({
                "order": [[4, "desc"]]
            });
        });
    </script>
@endpush
