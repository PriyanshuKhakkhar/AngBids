@extends('admin.layouts.admin')

@section('title', 'Settings - LaraBids Admin')

@push('styles')
<style>
    /* Settings Page Custom Styles */
    .settings-nav .nav-link {
        color: #5a5c69;
        font-weight: 600;
        padding: 0.85rem 1.25rem;
        border: none;
        border-left: 3px solid transparent;
        border-radius: 0;
        transition: all 0.25s ease;
        font-size: 0.88rem;
    }
    .settings-nav .nav-link:hover {
        color: #4e73df;
        background: rgba(78, 115, 223, 0.05);
        border-left-color: rgba(78, 115, 223, 0.3);
    }
    .settings-nav .nav-link.active {
        color: #4e73df;
        background: rgba(78, 115, 223, 0.08);
        border-left-color: #4e73df;
    }
    .settings-nav .nav-link i {
        width: 22px;
        text-align: center;
        margin-right: 10px;
        font-size: 0.95rem;
    }
    .settings-card {
        border: none;
        border-radius: 0.5rem;
        transition: box-shadow 0.3s ease;
    }
    .settings-card:hover {
        box-shadow: 0 0.25rem 1rem rgba(0, 0, 0, 0.1) !important;
    }
    .settings-section-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #2e3a59;
        margin-bottom: 0.25rem;
    }
    .settings-section-desc {
        font-size: 0.82rem;
        color: #858796;
        margin-bottom: 1.5rem;
    }
    .form-control:focus, .custom-select:focus {
        border-color: #bac8f3;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.15);
    }
    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 26px;
    }
    .toggle-switch input { opacity: 0; width: 0; height: 0; }
    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: #d1d3e2;
        transition: 0.3s;
        border-radius: 26px;
    }
    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 20px;
        width: 20px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: 0.3s;
        border-radius: 50%;
        box-shadow: 0 1px 3px rgba(0,0,0,0.15);
    }
    .toggle-switch input:checked + .toggle-slider {
        background-color: #1cc88a;
    }
    .toggle-switch input:checked + .toggle-slider:before {
        transform: translateX(24px);
    }
    .setting-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 0;
        border-bottom: 1px solid #f0f0f5;
    }
    .setting-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    .setting-item:first-child {
        padding-top: 0;
    }
    .setting-item-info h6 {
        font-weight: 600;
        color: #2e3a59;
        margin-bottom: 0.15rem;
        font-size: 0.9rem;
    }
    .setting-item-info p {
        font-size: 0.78rem;
        color: #858796;
        margin-bottom: 0;
    }
    .color-swatch {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        border: 2px solid #e3e6f0;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-block;
    }
    .color-swatch:hover {
        transform: scale(1.15);
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
    .color-swatch.active {
        border-color: #4e73df;
        box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.25);
    }
    .info-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.3rem 0.65rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .stat-mini-card {
        background: linear-gradient(135deg, #f8f9fc 0%, #fff 100%);
        border: 1px solid #e3e6f0;
        border-radius: 0.5rem;
        padding: 1rem;
        text-align: center;
        transition: all 0.25s;
    }
    .stat-mini-card:hover {
        border-color: #bac8f3;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(78, 115, 223, 0.1);
    }
    .stat-mini-card i {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
        color: #4e73df;
    }
    .stat-mini-card .stat-value {
        font-size: 1.25rem;
        font-weight: 700;
        color: #2e3a59;
    }
    .stat-mini-card .stat-label {
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #858796;
        font-weight: 600;
    }
    .timezone-badge {
        background: #eef2ff;
        color: #4e73df;
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        font-size: 0.82rem;
        font-weight: 600;
    }
    .version-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    .file-upload-zone {
        border: 2px dashed #d1d3e2;
        border-radius: 0.5rem;
        padding: 2rem;
        text-align: center;
        transition: all 0.3s;
        cursor: pointer;
        background: #fcfcff;
    }
    .file-upload-zone:hover {
        border-color: #4e73df;
        background: rgba(78, 115, 223, 0.03);
    }
    .file-upload-zone i {
        font-size: 2rem;
        color: #b7bfd4;
        margin-bottom: 0.5rem;
    }
    .preview-logo {
        width: 80px;
        height: 80px;
        border-radius: 12px;
        object-fit: cover;
        border: 2px solid #e3e6f0;
    }
    .btn-save-settings {
        padding: 0.6rem 2rem;
        font-weight: 600;
        border-radius: 8px;
        transition: all 0.25s;
    }
    .btn-save-settings:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(78, 115, 223, 0.35);
    }
    .danger-zone-card {
        border: 1px solid #f5c6cb !important;
        background: #fff5f5;
    }
    .danger-zone-card .card-header {
        background: #fef0f0;
        border-bottom-color: #f5c6cb;
    }
    @media (max-width: 991px) {
        .settings-nav {
            flex-direction: row !important;
            overflow-x: auto;
            white-space: nowrap;
            border-bottom: 1px solid #e3e6f0;
            margin-bottom: 1.5rem;
        }
        .settings-nav .nav-link {
            border-left: none;
            border-bottom: 3px solid transparent;
            display: inline-block;
        }
        .settings-nav .nav-link.active {
            border-left: none;
            border-bottom-color: #4e73df;
        }
    }
</style>
@endpush

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">System Settings</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">System Settings</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        {{-- Sidebar Navigation --}}
        <div class="col-lg-3 mb-4">
            <div class="card settings-card shadow">
                <div class="card-body p-0">
                    <div class="nav flex-column settings-nav" id="settingsTab" role="tablist">
                        <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab">
                            <i class="fas fa-sliders-h"></i> General
                        </a>
                        <a class="nav-link" id="auction-tab" data-toggle="tab" href="#auction" role="tab">
                            <i class="fas fa-gavel"></i> Auction Rules
                        </a>
                        <a class="nav-link" id="email-tab" data-toggle="tab" href="#email" role="tab">
                            <i class="fas fa-envelope"></i> Email & Notifications
                        </a>
                        <a class="nav-link" id="payment-tab" data-toggle="tab" href="#payment" role="tab">
                            <i class="fas fa-credit-card"></i> Payment Gateways
                        </a>
                        <a class="nav-link" id="appearance-tab" data-toggle="tab" href="#appearance" role="tab">
                            <i class="fas fa-palette"></i> Appearance
                        </a>
                        <a class="nav-link" id="security-tab" data-toggle="tab" href="#security" role="tab">
                            <i class="fas fa-shield-alt"></i> Security
                        </a>
                        <a class="nav-link" id="system-tab" data-toggle="tab" href="#system" role="tab">
                            <i class="fas fa-server"></i> System Info
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tab Content --}}
        <div class="col-lg-9">
            <div class="tab-content" id="settingsTabContent">

                {{-- ===== GENERAL SETTINGS ===== --}}
                <div class="tab-pane fade show active" id="general" role="tabpanel">
                    <div class="card settings-card shadow mb-4">
                        <div class="card-header py-3 d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="settings-section-title m-0"><i class="fas fa-sliders-h text-primary mr-2"></i>General Configuration</h6>
                            </div>
                            <span class="info-badge bg-light text-success"><i class="fas fa-check-circle mr-1"></i>Saved</span>
                        </div>
                        <div class="card-body">
                            <p class="settings-section-desc">Basic platform settings that control the core behavior of LaraBids.</p>

                            <div class="form-group row">
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold small">Site Name</label>
                                    <input type="text" class="form-control" value="LaraBids" placeholder="Your site name">
                                    <small class="text-muted">Displayed in headers, emails, and browser tab.</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold small">Tagline</label>
                                    <input type="text" class="form-control" value="Your Premier Online Auction Platform" placeholder="A short tagline">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold small">Admin Email</label>
                                    <input type="email" class="form-control" value="admin@larabids.com" placeholder="admin@example.com">
                                    <small class="text-muted">System notifications will be sent to this email.</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold small">Support Email</label>
                                    <input type="email" class="form-control" value="support@larabids.com" placeholder="support@example.com">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-4 mb-3">
                                    <label class="font-weight-bold small">Default Currency</label>
                                    <select class="custom-select">
                                        <option selected>INR (₹) - Indian Rupee</option>
                                        <option>USD ($) - US Dollar</option>
                                        <option>EUR (€) - Euro</option>
                                        <option>GBP (£) - Pound Sterling</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="font-weight-bold small">Timezone</label>
                                    <select class="custom-select">
                                        <option selected>Asia/Kolkata (IST)</option>
                                        <option>America/New_York (EST)</option>
                                        <option>Europe/London (GMT)</option>
                                        <option>Asia/Dubai (GST)</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="font-weight-bold small">Date Format</label>
                                    <select class="custom-select">
                                        <option selected>DD/MM/YYYY</option>
                                        <option>MM/DD/YYYY</option>
                                        <option>YYYY-MM-DD</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold small">Phone Number</label>
                                    <input type="text" class="form-control" value="+91 98765 43210" placeholder="+91 XXXXX XXXXX">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold small">Address</label>
                                    <input type="text" class="form-control" value="Mumbai, Maharashtra, India" placeholder="Office address">
                                </div>
                            </div>

                            <hr>
                            <div class="text-right">
                                <button type="button" class="btn btn-light mr-2">Reset</button>
                                <button type="button" class="btn btn-primary btn-save-settings shadow-sm">
                                    <i class="fas fa-save mr-1"></i> Save Changes
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===== AUCTION RULES ===== --}}
                <div class="tab-pane fade" id="auction" role="tabpanel">
                    <div class="card settings-card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="settings-section-title m-0"><i class="fas fa-gavel text-primary mr-2"></i>Auction Rules & Configuration</h6>
                        </div>
                        <div class="card-body">
                            <p class="settings-section-desc">Control how auctions are created, managed, and closed on the platform.</p>

                            <div class="form-group row">
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold small">Commission Rate (%)</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" value="10" min="0" max="100">
                                        <div class="input-group-append">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                    <small class="text-muted">Platform fee deducted from each successful auction sale.</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold small">Minimum Bid Increment (₹)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">₹</span>
                                        </div>
                                        <input type="number" class="form-control" value="100" min="1">
                                    </div>
                                    <small class="text-muted">Minimum amount a new bid must exceed the current bid by.</small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold small">Max Auction Duration (Days)</label>
                                    <input type="number" class="form-control" value="30" min="1" max="90">
                                    <small class="text-muted">Maximum number of days an auction can remain open.</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold small">Edit Grace Period (Hours)</label>
                                    <input type="number" class="form-control" value="24" min="1">
                                    <small class="text-muted">Time allowed to edit a pending auction after creation.</small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold small">Max Images Per Auction</label>
                                    <input type="number" class="form-control" value="5" min="1" max="20">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold small">Max Image Size (MB)</label>
                                    <input type="number" class="form-control" value="2" min="1" max="10">
                                </div>
                            </div>

                            <hr class="my-3">

                            <div class="setting-item">
                                <div class="setting-item-info">
                                    <h6>Require Admin Approval</h6>
                                    <p>New auctions must be approved by admin before going live.</p>
                                </div>
                                <label class="toggle-switch mb-0">
                                    <input type="checkbox" checked>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>

                            <div class="setting-item">
                                <div class="setting-item-info">
                                    <h6>Auto-Close Expired Auctions</h6>
                                    <p>Automatically close auctions when their end time passes.</p>
                                </div>
                                <label class="toggle-switch mb-0">
                                    <input type="checkbox" checked>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>

                            <div class="setting-item">
                                <div class="setting-item-info">
                                    <h6>Allow Auto Bidding</h6>
                                    <p>Users can set a maximum bid and the system bids automatically.</p>
                                </div>
                                <label class="toggle-switch mb-0">
                                    <input type="checkbox" checked>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>

                            <div class="setting-item">
                                <div class="setting-item-info">
                                    <h6>Re-approval on Edit</h6>
                                    <p>Require re-approval when an approved auction is edited.</p>
                                </div>
                                <label class="toggle-switch mb-0">
                                    <input type="checkbox" checked>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>

                            <hr>
                            <div class="text-right">
                                <button type="button" class="btn btn-light mr-2">Reset</button>
                                <button type="button" class="btn btn-primary btn-save-settings shadow-sm">
                                    <i class="fas fa-save mr-1"></i> Save Changes
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===== EMAIL & NOTIFICATIONS ===== --}}
                <div class="tab-pane fade" id="email" role="tabpanel">
                    <div class="card settings-card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="settings-section-title m-0"><i class="fas fa-envelope text-primary mr-2"></i>Email Configuration</h6>
                        </div>
                        <div class="card-body">
                            <p class="settings-section-desc">Configure SMTP settings for outgoing emails from the platform.</p>

                            <div class="form-group row">
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold small">SMTP Host</label>
                                    <input type="text" class="form-control" value="smtp.gmail.com" placeholder="smtp.example.com">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="font-weight-bold small">SMTP Port</label>
                                    <input type="number" class="form-control" value="587">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="font-weight-bold small">Encryption</label>
                                    <select class="custom-select">
                                        <option>None</option>
                                        <option>SSL</option>
                                        <option selected>TLS</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold small">SMTP Username</label>
                                    <input type="text" class="form-control" value="noreply@larabids.com" placeholder="username">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold small">SMTP Password</label>
                                    <input type="password" class="form-control" value="••••••••••" placeholder="password">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold small">From Name</label>
                                    <input type="text" class="form-control" value="LaraBids" placeholder="Sender name">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold small">From Email</label>
                                    <input type="email" class="form-control" value="noreply@larabids.com" placeholder="Sender email">
                                </div>
                            </div>

                            <div class="text-right mb-3">
                                <button type="button" class="btn btn-outline-info btn-sm">
                                    <i class="fas fa-paper-plane mr-1"></i> Send Test Email
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Notification Preferences --}}
                    <div class="card settings-card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="settings-section-title m-0"><i class="fas fa-bell text-primary mr-2"></i>Notification Preferences</h6>
                        </div>
                        <div class="card-body">
                            <p class="settings-section-desc">Choose which events trigger email notifications.</p>

                            <div class="setting-item">
                                <div class="setting-item-info">
                                    <h6>New User Registration</h6>
                                    <p>Get notified when a new user registers on the platform.</p>
                                </div>
                                <label class="toggle-switch mb-0">
                                    <input type="checkbox" checked>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>

                            <div class="setting-item">
                                <div class="setting-item-info">
                                    <h6>New Auction Submitted</h6>
                                    <p>Get notified when a seller submits a new auction for approval.</p>
                                </div>
                                <label class="toggle-switch mb-0">
                                    <input type="checkbox" checked>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>

                            <div class="setting-item">
                                <div class="setting-item-info">
                                    <h6>Auction Ended</h6>
                                    <p>Notify when an auction reaches its end time.</p>
                                </div>
                                <label class="toggle-switch mb-0">
                                    <input type="checkbox" checked>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>

                            <div class="setting-item">
                                <div class="setting-item-info">
                                    <h6>Payment Received</h6>
                                    <p>Notify admin when a payment is successfully processed.</p>
                                </div>
                                <label class="toggle-switch mb-0">
                                    <input type="checkbox">
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>

                            <div class="setting-item">
                                <div class="setting-item-info">
                                    <h6>Contact Form Submissions</h6>
                                    <p>Receive emails when users submit the contact form.</p>
                                </div>
                                <label class="toggle-switch mb-0">
                                    <input type="checkbox" checked>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>

                            <hr>
                            <div class="text-right">
                                <button type="button" class="btn btn-light mr-2">Reset</button>
                                <button type="button" class="btn btn-primary btn-save-settings shadow-sm">
                                    <i class="fas fa-save mr-1"></i> Save Changes
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===== PAYMENT GATEWAYS ===== --}}
                <div class="tab-pane fade" id="payment" role="tabpanel">
                    <div class="card settings-card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="settings-section-title m-0"><i class="fas fa-credit-card text-primary mr-2"></i>Payment Gateways</h6>
                        </div>
                        <div class="card-body">
                            <p class="settings-section-desc">Manage payment integrations and transaction settings.</p>

                            {{-- Stripe --}}
                            <div class="card border mb-3">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width:42px;height:42px;">
                                                <i class="fab fa-stripe-s"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 font-weight-bold">Stripe</h6>
                                                <small class="text-muted">Credit/Debit card payments</small>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span class="badge badge-success mr-3 py-1 px-2">Active</span>
                                            <label class="toggle-switch mb-0">
                                                <input type="checkbox" checked>
                                                <span class="toggle-slider"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-0">
                                        <div class="col-md-6 mb-2">
                                            <label class="font-weight-bold small">Publishable Key</label>
                                            <input type="text" class="form-control form-control-sm" value="pk_test_••••••••••••••••••••">
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="font-weight-bold small">Secret Key</label>
                                            <input type="password" class="form-control form-control-sm" value="sk_test_••••••••••••••••••••">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- PayPal --}}
                            <div class="card border mb-3">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width:42px;height:42px;background:#003087;">
                                                <i class="fab fa-paypal"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 font-weight-bold">PayPal</h6>
                                                <small class="text-muted">PayPal & Venmo payments</small>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span class="badge badge-secondary mr-3 py-1 px-2">Inactive</span>
                                            <label class="toggle-switch mb-0">
                                                <input type="checkbox">
                                                <span class="toggle-slider"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-0">
                                        <div class="col-md-6 mb-2">
                                            <label class="font-weight-bold small">Client ID</label>
                                            <input type="text" class="form-control form-control-sm" placeholder="Enter PayPal Client ID" disabled>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="font-weight-bold small">Client Secret</label>
                                            <input type="password" class="form-control form-control-sm" placeholder="Enter PayPal Client Secret" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Razorpay --}}
                            <div class="card border mb-3">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width:42px;height:42px;background:#072654;">
                                                <i class="fas fa-rupee-sign"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 font-weight-bold">Razorpay</h6>
                                                <small class="text-muted">UPI, Netbanking & Wallets (India)</small>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span class="badge badge-secondary mr-3 py-1 px-2">Inactive</span>
                                            <label class="toggle-switch mb-0">
                                                <input type="checkbox">
                                                <span class="toggle-slider"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-0">
                                        <div class="col-md-6 mb-2">
                                            <label class="font-weight-bold small">Key ID</label>
                                            <input type="text" class="form-control form-control-sm" placeholder="Enter Razorpay Key ID" disabled>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="font-weight-bold small">Key Secret</label>
                                            <input type="password" class="form-control form-control-sm" placeholder="Enter Razorpay Key Secret" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <div class="text-right">
                                <button type="button" class="btn btn-primary btn-save-settings shadow-sm">
                                    <i class="fas fa-save mr-1"></i> Save Changes
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===== APPEARANCE ===== --}}
                <div class="tab-pane fade" id="appearance" role="tabpanel">
                    <div class="card settings-card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="settings-section-title m-0"><i class="fas fa-palette text-primary mr-2"></i>Appearance & Branding</h6>
                        </div>
                        <div class="card-body">
                            <p class="settings-section-desc">Customize the look and feel of your auction platform.</p>

                            {{-- Logo Upload --}}
                            <div class="form-group row align-items-center mb-4">
                                <div class="col-md-3 text-center">
                                    <div class="bg-gradient-primary text-white rounded d-flex align-items-center justify-content-center mx-auto" style="width:80px;height:80px;font-size:2rem;border-radius:12px!important;">
                                        <i class="fas fa-gavel"></i>
                                    </div>
                                    <small class="text-muted d-block mt-2">Current Logo</small>
                                </div>
                                <div class="col-md-9">
                                    <div class="file-upload-zone">
                                        <i class="fas fa-cloud-upload-alt d-block"></i>
                                        <p class="mb-1 font-weight-bold small">Click to upload a new logo</p>
                                        <p class="mb-0 text-muted" style="font-size:0.75rem;">PNG, JPG or SVG (max. 1MB, recommended 200×200px)</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Favicon --}}
                            <div class="form-group row align-items-center mb-4">
                                <div class="col-md-3 text-center">
                                    <div class="bg-light border rounded d-flex align-items-center justify-content-center mx-auto" style="width:48px;height:48px;">
                                        <i class="fas fa-gavel text-primary"></i>
                                    </div>
                                    <small class="text-muted d-block mt-2">Favicon</small>
                                </div>
                                <div class="col-md-9">
                                    <div class="file-upload-zone" style="padding:1rem;">
                                        <i class="fas fa-image d-inline mr-2" style="font-size:1.2rem;"></i>
                                        <span class="small font-weight-bold">Upload Favicon</span>
                                        <span class="text-muted small ml-2">(ICO, PNG — 32×32px)</span>
                                    </div>
                                </div>
                            </div>

                            <hr>
                            {{-- Primary Color --}}
                            <label class="font-weight-bold small">Primary Theme Color</label>
                            <div class="d-flex align-items-center mb-3" style="gap:10px;">
                                <span class="color-swatch active" style="background:#4e73df;" title="#4e73df"></span>
                                <span class="color-swatch" style="background:#1cc88a;" title="#1cc88a"></span>
                                <span class="color-swatch" style="background:#e74a3b;" title="#e74a3b"></span>
                                <span class="color-swatch" style="background:#f6c23e;" title="#f6c23e"></span>
                                <span class="color-swatch" style="background:#36b9cc;" title="#36b9cc"></span>
                                <span class="color-swatch" style="background:#6f42c1;" title="#6f42c1"></span>
                                <span class="color-swatch" style="background:#e83e8c;" title="#e83e8c"></span>
                                <span class="color-swatch" style="background:#20c997;" title="#20c997"></span>
                            </div>
                            <small class="text-muted d-block mb-4">Applies to sidebar, buttons, links, and active elements.</small>

                            {{-- Footer Text --}}
                            <div class="form-group">
                                <label class="font-weight-bold small">Footer Copyright Text</label>
                                <input type="text" class="form-control" value="© 2026 LaraBids. All Rights Reserved.">
                            </div>

                            <div class="setting-item">
                                <div class="setting-item-info">
                                    <h6>Show "Powered by LaraBids"</h6>
                                    <p>Display branding in the website footer.</p>
                                </div>
                                <label class="toggle-switch mb-0">
                                    <input type="checkbox" checked>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>

                            <hr>
                            <div class="text-right">
                                <button type="button" class="btn btn-light mr-2">Reset</button>
                                <button type="button" class="btn btn-primary btn-save-settings shadow-sm">
                                    <i class="fas fa-save mr-1"></i> Save Changes
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===== SECURITY ===== --}}
                <div class="tab-pane fade" id="security" role="tabpanel">
                    {{-- Password & Auth --}}
                    <div class="card settings-card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="settings-section-title m-0"><i class="fas fa-lock text-primary mr-2"></i>Password & Authentication</h6>
                        </div>
                        <div class="card-body">
                            <p class="settings-section-desc">Change admin password and manage authentication security.</p>

                            <div class="form-group row">
                                <div class="col-md-12 mb-3">
                                    <label class="font-weight-bold small">Current Password</label>
                                    <input type="password" class="form-control" placeholder="Enter current password">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold small">New Password</label>
                                    <input type="password" class="form-control" placeholder="Enter new password">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold small">Confirm New Password</label>
                                    <input type="password" class="form-control" placeholder="Confirm new password">
                                </div>
                            </div>

                            <div class="text-right mb-3">
                                <button type="button" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-key mr-1"></i> Update Password
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Security Settings --}}
                    <div class="card settings-card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="settings-section-title m-0"><i class="fas fa-shield-alt text-primary mr-2"></i>Security Settings</h6>
                        </div>
                        <div class="card-body">
                            <div class="setting-item">
                                <div class="setting-item-info">
                                    <h6>Two-Factor Authentication</h6>
                                    <p>Add an extra layer of security to your admin account.</p>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="badge badge-warning mr-3 py-1 px-2">Not Enabled</span>
                                    <button type="button" class="btn btn-outline-success btn-sm">Enable</button>
                                </div>
                            </div>

                            <div class="setting-item">
                                <div class="setting-item-info">
                                    <h6>Login Attempt Lockout</h6>
                                    <p>Lock account after 5 failed login attempts for 30 minutes.</p>
                                </div>
                                <label class="toggle-switch mb-0">
                                    <input type="checkbox" checked>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>

                            <div class="setting-item">
                                <div class="setting-item-info">
                                    <h6>Force HTTPS</h6>
                                    <p>Redirect all HTTP traffic to HTTPS for secure connections.</p>
                                </div>
                                <label class="toggle-switch mb-0">
                                    <input type="checkbox" checked>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>

                            <div class="setting-item">
                                <div class="setting-item-info">
                                    <h6>Session Timeout (Minutes)</h6>
                                    <p>Automatically log out inactive admin sessions.</p>
                                </div>
                                <div style="width:100px;">
                                    <input type="number" class="form-control form-control-sm text-center" value="120">
                                </div>
                            </div>

                            <hr>
                            <div class="text-right">
                                <button type="button" class="btn btn-primary btn-save-settings shadow-sm">
                                    <i class="fas fa-save mr-1"></i> Save Changes
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Danger Zone --}}
                    <div class="card settings-card danger-zone-card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="settings-section-title m-0 text-danger"><i class="fas fa-exclamation-triangle mr-2"></i>Danger Zone</h6>
                        </div>
                        <div class="card-body">
                            <div class="setting-item">
                                <div class="setting-item-info">
                                    <h6 class="text-danger">Maintenance Mode</h6>
                                    <p>Take the site offline for maintenance. Only admins can access.</p>
                                </div>
                                <button type="button" class="btn btn-outline-warning btn-sm">
                                    <i class="fas fa-tools mr-1"></i> Enable
                                </button>
                            </div>
                            <div class="setting-item">
                                <div class="setting-item-info">
                                    <h6 class="text-danger">Clear All Cache</h6>
                                    <p>Clear application cache, config cache, route cache, and views.</p>
                                </div>
                                <button type="button" class="btn btn-outline-danger btn-sm">
                                    <i class="fas fa-broom mr-1"></i> Clear Cache
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===== SYSTEM INFO ===== --}}
                <div class="tab-pane fade" id="system" role="tabpanel">
                    <div class="card settings-card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="settings-section-title m-0"><i class="fas fa-server text-primary mr-2"></i>System Information</h6>
                        </div>
                        <div class="card-body">
                            <p class="settings-section-desc">An overview of your server environment and application details.</p>

                            <div class="row mb-4">
                                <div class="col-md-3 col-6 mb-3">
                                    <div class="stat-mini-card">
                                        <i class="fab fa-laravel d-block text-danger"></i>
                                        <div class="stat-value">10.x</div>
                                        <div class="stat-label">Laravel</div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6 mb-3">
                                    <div class="stat-mini-card">
                                        <i class="fab fa-php d-block" style="color:#777BB3;"></i>
                                        <div class="stat-value">8.2</div>
                                        <div class="stat-label">PHP Version</div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6 mb-3">
                                    <div class="stat-mini-card">
                                        <i class="fas fa-database d-block text-info"></i>
                                        <div class="stat-value">MySQL</div>
                                        <div class="stat-label">Database</div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6 mb-3">
                                    <div class="stat-mini-card">
                                        <i class="fas fa-hdd d-block text-success"></i>
                                        <div class="stat-value">2.4 GB</div>
                                        <div class="stat-label">Storage Used</div>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-sm table-hover mb-0">
                                    <tbody>
                                        <tr>
                                            <td class="font-weight-bold text-gray-800" style="width:40%;">Application Name</td>
                                            <td>LaraBids - Online Auction System</td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold text-gray-800">Application Version</td>
                                            <td><span class="badge badge-primary py-1 px-2">v1.0.0</span></td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold text-gray-800">Environment</td>
                                            <td><span class="badge badge-warning py-1 px-2">Local</span></td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold text-gray-800">Debug Mode</td>
                                            <td><span class="badge badge-danger py-1 px-2">Enabled</span></td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold text-gray-800">App URL</td>
                                            <td><code>http://localhost:8000</code></td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold text-gray-800">Server OS</td>
                                            <td>Windows 11 (x64)</td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold text-gray-800">Web Server</td>
                                            <td>Apache / Nginx (via Laravel Artisan)</td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold text-gray-800">Max Upload Size</td>
                                            <td>2 MB</td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold text-gray-800">Memory Limit</td>
                                            <td>256 MB</td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold text-gray-800">Cache Driver</td>
                                            <td>File</td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold text-gray-800">Queue Driver</td>
                                            <td>Sync</td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold text-gray-800">Mail Driver</td>
                                            <td>SMTP</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Server Health --}}
                    <div class="card settings-card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="settings-section-title m-0"><i class="fas fa-heartbeat text-primary mr-2"></i>Server Health</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <span class="small font-weight-bold">CPU Usage</span>
                                        <span class="small text-muted">23%</span>
                                    </div>
                                    <div class="progress" style="height:8px;border-radius:4px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width:23%"></div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <span class="small font-weight-bold">Memory Usage</span>
                                        <span class="small text-muted">61%</span>
                                    </div>
                                    <div class="progress" style="height:8px;border-radius:4px;">
                                        <div class="progress-bar bg-info" role="progressbar" style="width:61%"></div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <span class="small font-weight-bold">Disk Usage</span>
                                        <span class="small text-muted">38%</span>
                                    </div>
                                    <div class="progress" style="height:8px;border-radius:4px;">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width:38%"></div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <span class="small font-weight-bold">Database Load</span>
                                        <span class="small text-muted">12%</span>
                                    </div>
                                    <div class="progress" style="height:8px;border-radius:4px;">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width:12%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Color swatch selection
    document.querySelectorAll('.color-swatch').forEach(function(swatch) {
        swatch.addEventListener('click', function() {
            document.querySelectorAll('.color-swatch').forEach(function(s) { s.classList.remove('active'); });
            this.classList.add('active');
        });
    });

    // Save button click feedback (static demo)
    document.querySelectorAll('.btn-save-settings').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-check mr-1"></i> Saved!';
            this.classList.remove('btn-primary');
            this.classList.add('btn-success');
            var self = this;
            setTimeout(function() {
                self.innerHTML = originalText;
                self.classList.remove('btn-success');
                self.classList.add('btn-primary');
            }, 1500);
        });
    });
</script>
@endpush



