import { Component, OnInit, inject, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { AdminService } from '../../services/admin.service';

@Component({
  selector: 'app-admin-dashboard',
  standalone: true,
  imports: [CommonModule],
  template: `
    <!-- Summary Cards -->
    <div class="row g-4">
      <div class="col-md-3" *ngFor="let card of stats()">
        <div class="glass-panel p-4 text-center border-0 h-100 shadow-sm transition-hover">
          <div class="mb-3 text-gold opacity-50"><i [class]="card.icon + ' fa-2x'"></i></div>
          <div class="h3 fw-bold mb-1 text-dark">{{ card.value }}</div>
          <div class="small text-secondary fw-bold text-uppercase tracking-wider">{{ card.label }}</div>
          <div class="mt-2 small" [class]="card.trend > 0 ? 'text-success' : 'text-danger'">
            <i [class]="card.trend > 0 ? 'fas fa-arrow-up' : 'fas fa-arrow-down'"></i> {{ card.trend }}%
            <span class="ms-1 text-secondary opacity-75 fw-normal">vs last week</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Charts & Activity -->
    <div class="row mt-5 g-4">
      <div class="col-lg-8">
        <div class="glass-panel p-4 h-100 shadow-sm border-0">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="h5 mb-0 fw-bold text-dark"><i class="fas fa-bolt text-gold me-2"></i>Recent System Activity</h3>
            <button class="btn btn-outline-gold btn-sm px-3 rounded-pill fw-bold">Live Feed <span class="pulse-dot ms-1"></span></button>
          </div>
          <div class="table-responsive">
            <table class="table table-hover border-transparent mb-0">
              <thead>
                <tr class="text-secondary small text-uppercase letter-spacing-1">
                  <th class="border-0 px-0">Action</th>
                  <th class="border-0 px-0">User</th>
                  <th class="border-0 px-0">Target</th>
                  <th class="border-0 text-end px-0">Time</th>
                </tr>
              </thead>
              <tbody>
                <tr *ngFor="let item of recentActivity()" class="align-middle">
                   <td class="px-0 py-3 border-opacity-10 border-dark small">
                     <span class="badge bg-gold-soft text-gold rounded-pill px-3 fw-bold">{{ item.action }}</span>
                   </td>
                   <td class="px-0 py-3 border-opacity-10 border-dark">
                     <div class="fw-bold small text-dark">{{ item.user }}</div>
                   </td>
                   <td class="px-0 py-3 border-opacity-10 border-dark">
                     <div class="text-secondary small">{{ item.target }}</div>
                   </td>
                   <td class="px-0 py-3 border-opacity-10 border-dark text-end">
                     <div class="text-secondary x-small fw-bold">{{ item.time }}</div>
                   </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      
      <div class="col-lg-4">
        <div class="glass-panel p-4 h-100 shadow-sm border-0">
          <h3 class="h5 mb-4 fw-bold text-dark"><i class="fas fa-server text-gold me-2"></i>Infrastructure Status</h3>
          <div class="d-flex flex-column gap-4">
             <div *ngFor="let system of systems()">
                <div class="d-flex justify-content-between mb-2">
                  <span class="small fw-bold text-secondary">{{ system.name }}</span>
                  <span class="badge bg-success-soft text-success px-2 py-1 x-small rounded-pill">{{ system.status }}</span>
                </div>
                <div class="progress" style="height: 6px; background: rgba(0,0,0,0.05)">
                  <div class="progress-bar bg-gold rounded-pill" role="progressbar" [style.width]="system.load + '%'"></div>
                </div>
                <div class="text-end mt-1">
                  <span class="x-small text-secondary fw-bold">{{ system.load }}% load</span>
                </div>
             </div>
          </div>
        </div>
      </div>
    </div>
  `
})
export class AdminDashboard implements OnInit {
  private adminService = inject(AdminService);

  stats = signal([
    { label: 'Live Auctions', value: '142', icon: 'fas fa-gavel', trend: 12 },
    { label: 'Active Users', value: '3,842', icon: 'fas fa-users', trend: 6 },
    { label: 'Revenue (Mtd)', value: '₹4.2M', icon: 'fas fa-coins', trend: -2 },
    { label: 'KYC Verification', value: '24', icon: 'fas fa-shield-alt', trend: 15 }
  ]);

  recentActivity = signal([
    { action: 'NEW AUCTION', user: 'Premium Seller', target: 'Vintage Watch #442', time: '2 mins ago' },
    { action: 'KYC VERIFIED', user: 'Anil Kumar', target: 'Verification Badge', time: '14 mins ago' },
    { action: 'BID PLACED', user: 'Master Bidder', target: 'Luxury Estate #12', time: '1 hr ago' },
    { action: 'USER BANNED', user: 'System Autocheck', target: 'BadActor_99', time: '2 hrs ago' }
  ]);

  systems = signal([
    { name: 'Core API Gateway', status: 'Healthy', load: 34 },
    { name: 'Media Storage (S3)', status: 'Healthy', load: 12 },
    { name: 'WebSocket Node', status: 'Healthy', load: 78 },
    { name: 'ElasticSearch Index', status: 'Healthy', load: 45 }
  ]);

  ngOnInit() {
    this.refreshData();
  }

  refreshData() {
    this.adminService.getDashboardStats().subscribe({
      next: (data) => {
        if (data.stats) this.stats.set(data.stats);
      },
      error: (err) => console.warn('Using dummy dashboard data. Backend not reachable.')
    });

    this.adminService.getRecentActivity().subscribe({
      next: (data) => this.recentActivity.set(data),
      error: (err) => console.warn('Using dummy activity data.')
    });
  }
}
