@extends('admin.layouts.admin')

@section('title', 'Notifications - LaraBids')

@section('content')
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Notifications</h1>

    <div class="row">

        <div class="col-lg-12">

            <!-- Recent Alerts -->
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div
                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Alerts</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                            aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Actions:</div>
                            <a class="dropdown-item" href="#">Mark all as read</a>
                            <a class="dropdown-item" href="#">Clear all</a>
                        </div>
                    </div>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="#"
                            class="list-group-item list-group-item-action flex-column align-items-start bg-light">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1 text-primary"><i class="fas fa-hammer mr-2"></i> New
                                    high bid on "Vintage Watch"</h5>
                                <small>10 mins ago</small>
                            </div>
                            <p class="mb-1">User "johndoe" placed a bid of ₹500.00.</p>
                        </a>
                        <a href="#"
                            class="list-group-item list-group-item-action flex-column align-items-start">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1 text-success"><i class="fas fa-donate mr-2"></i> Payment
                                    received for Auction #4502</h5>
                                <small class="text-muted">3 hours ago</small>
                            </div>
                            <p class="mb-1">Payment of ₹1,200.00 has been verified via PayPal.</p>
                        </a>
                        <a href="#"
                            class="list-group-item list-group-item-action flex-column align-items-start">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1 text-warning"><i
                                        class="fas fa-exclamation-triangle mr-2"></i> Report: Item #220
                                </h5>
                                <small class="text-muted">Yesterday</small>
                            </div>
                            <p class="mb-1">User "jane_smith" has reported item #220 for "Counterfeit
                                Item".</p>
                        </a>
                        <a href="#"
                            class="list-group-item list-group-item-action flex-column align-items-start">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1 text-info"><i class="fas fa-user-plus mr-2"></i> New
                                    User Registration</h5>
                                <small class="text-muted">2 days ago</small>
                            </div>
                            <p class="mb-1">New user "auction_king" has registered.</p>
                        </a>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection
