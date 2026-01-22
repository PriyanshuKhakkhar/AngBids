@extends('admin.layouts.admin')

@section('title', 'Admin Profile - LaraBids')

@section('content')
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Admin Profile</h1>

    <div class="row">

        <div class="col-lg-4">
            <!-- Profile Picture Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Profile Picture</h6>
                </div>
                <div class="card-body text-center">
                    <img class="img-profile rounded-circle mb-3" src="{{ asset('admin-assets/img/undraw_profile.svg') }}"
                        style="width: 150px; height: 150px;">
                    <div class="small font-italic text-muted mb-4">JPG or PNG no larger than 5 MB</div>
                    <button class="btn btn-primary" type="button">Upload new image</button>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <!-- Account Details Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Account Details</h6>
                </div>
                <div class="card-body">
                    <form>
                        <div class="mb-3">
                            <label class="small mb-1" for="inputUsername">Username (how your name will
                                appear to other users on the site)</label>
                            <input class="form-control" id="inputUsername" type="text"
                                placeholder="Enter your username" value="adminuser">
                        </div>
                        <div class="row gx-3 mb-3">
                            <div class="col-md-6">
                                <label class="small mb-1" for="inputFirstName">First name</label>
                                <input class="form-control" id="inputFirstName" type="text"
                                    placeholder="Enter your first name" value="Admin">
                            </div>
                            <div class="col-md-6">
                                <label class="small mb-1" for="inputLastName">Last name</label>
                                <input class="form-control" id="inputLastName" type="text"
                                    placeholder="Enter your last name" value="User">
                            </div>
                        </div>
                        <div class="row gx-3 mb-3">
                            <div class="col-md-6">
                                <label class="small mb-1" for="inputOrgName">Organization name</label>
                                <input class="form-control" id="inputOrgName" type="text"
                                    placeholder="Enter your organization name"
                                    value="Auction House Inc.">
                            </div>
                            <div class="col-md-6">
                                <label class="small mb-1" for="inputLocation">Location</label>
                                <input class="form-control" id="inputLocation" type="text"
                                    placeholder="Enter your location" value="San Francisco, CA">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="inputEmailAddress">Email address</label>
                            <input class="form-control" id="inputEmailAddress" type="email"
                                placeholder="Enter your email address" value="admin@auctionapp.com">
                        </div>
                        <div class="row gx-3 mb-3">
                            <div class="col-md-6">
                                <label class="small mb-1" for="inputPhone">Phone number</label>
                                <input class="form-control" id="inputPhone" type="tel"
                                    placeholder="Enter your phone number" value="555-123-4567">
                            </div>
                            <div class="col-md-6">
                                <label class="small mb-1" for="inputBirthday">Birthday</label>
                                <input class="form-control" id="inputBirthday" type="text"
                                    name="birthday" placeholder="Enter your birthday"
                                    value="06/10/1988">
                            </div>
                        </div>
                        <button class="btn btn-primary" type="button">Save changes</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection
