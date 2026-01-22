@extends('admin.layouts.admin')

@section('title', 'Reports & Analytics - LaraBids')

@section('content')
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Auction Analytics & Reports</h1>
    <p class="mb-4">Visualize your platform performance, bidding trends, and category distribution.</p>

    <!-- Content Row -->
    <div class="row">

        <div class="col-xl-8 col-lg-7">

            <!-- Area Chart -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Monthly Revenue Growth</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="myAreaChart"></canvas>
                    </div>
                    <hr>
                    Showing the total transaction volume and platform earnings over the last 12 months.
                </div>
            </div>

            <!-- Bar Chart -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Auctions by Year</h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar">
                        <canvas id="myBarChart"></canvas>
                    </div>
                    <hr>
                    Year-over-year comparison of total items listed on the platform.
                </div>
            </div>

        </div>

        <!-- Donut Chart -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top Categories</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="chart-pie pt-4">
                        <canvas id="myPieChart"></canvas>
                    </div>
                    <hr>
                    Breakdown of auctions by category (Electronics, Watches, Collectibles).
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Page level plugins -->
    <script src="{{ asset('admin-assets/vendor/chart.js/Chart.min.js') }}"></script>

    <!-- Page level custom scripts -->
    <script src="{{ asset('admin-assets/js/demo/chart-area-demo.js') }}"></script>
    <script src="{{ asset('admin-assets/js/demo/chart-pie-demo.js') }}"></script>
    <script src="{{ asset('admin-assets/js/demo/chart-bar-demo.js') }}"></script>
@endpush
