import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-admin-dashboard',
  standalone: true,
  imports: [CommonModule],
  template: `
    <div class="admin-wrapper d-flex min-vh-100 bg-light">
      <!-- Sidebar -->
      <aside class="admin-sidebar bg-dark text-white p-3 d-none d-md-block" style="width: 260px;">
        <div class="sidebar-brand mb-4 py-2 border-bottom border-secondary text-center">
            <h4 class="m-0 text-gold fw-bold">AngBids <span class="text-white fs-6">Admin</span></h4>
        </div>
        
        <ul class="nav flex-column gap-2 mb-5">
            <li class="nav-item">
                <a class="nav-link text-white active bg-primary rounded-3" href="javascript:void(0)">
                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white-50" href="javascript:void(0)">
                    <i class="fas fa-gavel me-2"></i> Manage Auctions
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white-50" href="javascript:void(0)">
                    <i class="fas fa-users me-2"></i> Manage Users
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white-50" href="javascript:void(0)">
                    <i class="fas fa-history me-2"></i> Manage Bids
                </a>
            </li>
        </ul>

        <div class="mt-auto p-3 bg-secondary bg-opacity-10 rounded-3">
            <small class="text-white-50 d-block">Logged in as:</small>
            <div class="fw-bold">Administrator</div>
        </div>
      </aside>

      <!-- Main Content -->
      <main class="flex-grow-1 p-4 overflow-auto">
        <!-- Header -->
        <header class="mb-4 pb-3 border-bottom">
            <h1 class="h3 fw-bold mb-1">Admin Dashboard</h1>
            <p class="text-secondary small mb-0">Monitor and manage platform activity</p>
        </header>

        <!-- Stats Grid -->
        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-primary border-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-uppercase fw-bold text-secondary mb-1 d-block" style="font-size: 0.7rem;">Total Users</small>
                            <h2 class="m-0 fw-bold">120</h2>
                        </div>
                        <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-3 p-3">
                            <i class="fas fa-users fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-success border-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-uppercase fw-bold text-secondary mb-1 d-block" style="font-size: 0.7rem;">Total Auctions</small>
                            <h2 class="m-0 fw-bold">45</h2>
                        </div>
                        <div class="icon-box bg-success bg-opacity-10 text-success rounded-3 p-3">
                            <i class="fas fa-gavel fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-warning border-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-uppercase fw-bold text-secondary mb-1 d-block" style="font-size: 0.7rem;">Total Bids</small>
                            <h2 class="m-0 fw-bold">310</h2>
                        </div>
                        <div class="icon-box bg-warning bg-opacity-10 text-warning rounded-3 p-3">
                            <i class="fas fa-history fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-info border-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-uppercase fw-bold text-secondary mb-1 d-block" style="font-size: 0.7rem;">Active Auctions</small>
                            <h2 class="m-0 fw-bold">18</h2>
                        </div>
                        <div class="icon-box bg-info bg-opacity-10 text-info rounded-3 p-3">
                            <i class="fas fa-bolt fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Placeholder for activity -->
        <div class="row g-4">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
                    <h5 class="fw-bold mb-4">Platform Overview</h5>
                    <div class="text-center py-5 text-secondary">
                        <i class="fas fa-chart-line fs-1 mb-3 opacity-25"></i>
                        <p>Detailed performance analytics will appear here.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
                    <h5 class="fw-bold mb-4">Live Activity</h5>
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex gap-3 small pb-2 border-bottom">
                            <div class="p-2 h-100 bg-light rounded text-primary"><i class="fas fa-user"></i></div>
                            <div>
                                <div class="fw-bold">New user registered</div>
                                <div class="text-muted">2 mins ago</div>
                            </div>
                        </div>
                        <div class="d-flex gap-3 small pb-2 border-bottom">
                            <div class="p-2 h-100 bg-light rounded text-success"><i class="fas fa-gavel"></i></div>
                            <div>
                                <div class="fw-bold">New bid placed on "MacBook Pro"</div>
                                <div class="text-muted">15 mins ago</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </main>
    </div>
  `,
  styles: [`
    .admin-sidebar .nav-link:hover {
        background: rgba(255,255,255,0.05);
        color: #fff !important;
    }
    .text-gold {
        color: #d4af37;
    }
    .bg-light {
        background-color: #f8f9fc !important;
    }
  `]
})
export class AdminDashboardComponent {}
