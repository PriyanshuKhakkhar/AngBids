import { Component, OnInit, signal } from '@angular/core';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-admin-dashboard',
  standalone: true,
  imports: [CommonModule],
  template: `
    <div class="row g-4">
      <div class="col-md-3" *ngFor="let card of stats()">
        <div class="glass-panel p-4 text-center border-0 h-100 shadow-sm">
          <div class="mb-3 text-gold opacity-50"><i [class]="card.icon + ' fa-2x'"></i></div>
          <div class="h3 fw-bold mb-1">{{ card.value }}</div>
          <div class="small text-secondary fw-semibold text-uppercase tracking-wider">{{ card.label }}</div>
          <div class="mt-2 small" [class]="card.trend > 0 ? 'text-success' : 'text-danger'">
            <i [class]="card.trend > 0 ? 'fas fa-arrow-up' : 'fas fa-arrow-down'"></i> {{ card.trend }}%
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-5 g-4">
      <div class="col-lg-8">
        <div class="glass-panel p-4 h-100 shadow-sm border-0">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="h5 mb-0 fw-bold">Recent System Activity</h3>
            <button class="btn btn-outline-gold btn-sm px-3">View Report</button>
          </div>
          <div class="table-responsive">
            <table class="table table-dark table-hover border-transparent">
              <thead>
                <tr class="text-secondary small text-uppercase">
                  <th class="border-0 px-0">Action</th>
                  <th class="border-0 px-0">User</th>
                  <th class="border-0 px-0">Target</th>
                  <th class="border-0 text-end px-0">Time</th>
                </tr>
              </thead>
              <tbody>
                <tr *ngFor="let item of recentActivity()">
                   <td class="px-0 py-3 border-opacity-10 border-white small align-middle">
                     <span class="badge bg-gold-soft text-gold rounded-pill px-3">{{ item.action }}</span>
                   </td>
                   <td class="px-0 py-3 border-opacity-10 border-white align-middle">
                     <div class="fw-bold small">{{ item.user }}</div>
                   </td>
                   <td class="px-0 py-3 border-opacity-10 border-white align-middle">
                     <div class="text-secondary small">{{ item.target }}</div>
                   </td>
                   <td class="px-0 py-3 border-opacity-10 border-white text-end align-middle">
                     <div class="text-secondary x-small">{{ item.time }}</div>
                   </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="glass-panel p-4 h-100 shadow-sm border-0">
          <h3 class="h5 mb-4 fw-bold">System Status</h3>
          <div class="d-flex flex-column gap-4">
             <div *ngFor="let system of systems()">
                <div class="d-flex justify-content-between mb-2">
                  <span class="small fw-semibold text-secondary">{{ system.name }}</span>
                  <span class="badge bg-gold-soft text-gold px-2 py-0 x-small">{{ system.status }}</span>
                </div>
                <div class="progress" style="height: 4px; background: rgba(255,255,255,0.05)">
                  <div class="progress-bar bg-gold" role="progressbar" [style.width]="system.load + '%'"></div>
                </div>
             </div>
          </div>
        </div>
      </div>
    </div>
  `
})
export class AdminDashboard implements OnInit {
  stats = signal([
    { label: 'Active Auctions', value: 142, icon: 'fas fa-gavel', trend: 12 },
    { label: 'Total Users', value: 3842, icon: 'fas fa-users', trend: 6 },
    { label: 'Gross Bids', value: '₹4.2M', icon: 'fas fa-coins', trend: -2 },
    { label: 'Pending KYC', value: 24, icon: 'fas fa-shield-alt', trend: 15 }
  ]);

  recentActivity = signal([
    { action: 'NEW AUCTION', user: 'Premium Seller', target: 'Vintage Watch #442', time: '2 mins ago' },
    { action: 'KYC VERIFIED', user: 'Anil Kumar', target: 'Verification Badge', time: '14 mins ago' },
    { action: 'BID PLACED', user: 'Master Bidder', target: 'Luxury Estate #12', time: '1 hr ago' },
    { action: 'USER BANNED', user: 'System Autocheck', target: 'BadActor_99', time: '2 hrs ago' }
  ]);

  systems = signal([
    { name: 'API Server', status: 'Healthy', load: 34 },
    { name: 'Image Processor', status: 'Healthy', load: 12 },
    { name: 'WebSocket Server', status: 'Healthy', load: 78 },
    { name: 'Database Clusters', status: 'Healthy', load: 45 }
  ]);

  ngOnInit() {}
}
