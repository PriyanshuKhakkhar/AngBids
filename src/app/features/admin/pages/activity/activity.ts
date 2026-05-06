import { Component, signal } from '@angular/core';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-admin-activity',
  standalone: true,
  imports: [CommonModule],
  template: `
    <div class="glass-panel p-4 shadow-sm border-0 h-100">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h5 mb-0 fw-bold">Live Activity Monitoring</h2>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-gold btn-sm px-4"><i class="fas fa-sync-alt me-2"></i>Refresh</button>
        </div>
      </div>

      <div class="list-group list-group-flush bg-transparent">
        <div *ngFor="let item of activity()" class="list-group-item bg-transparent border-0 py-4 px-0">
          <div class="d-flex justify-content-between align-items-start">
            <div class="d-flex gap-3">
              <div class="activity-icon flex-shrink-0">
                <span class="badge bg-gold bg-opacity-10 text-gold px-3 py-2 rounded-3 border border-gold border-opacity-10">
                  <i [class]="getIcon(item.type)"></i>
                </span>
              </div>
              <div>
                <div class="fw-bold small text-dark">{{ item.message }}</div>
                <div class="text-secondary x-small mt-1 d-flex align-items-center gap-3">
                  <span class="d-flex align-items-center gap-1"><i class="fas fa-user-circle text-primary opacity-50"></i> {{ item.user }}</span>
                  <span class="d-flex align-items-center gap-1"><i class="fas fa-link text-primary opacity-50"></i> {{ item.target }}</span>
                </div>
              </div>
            </div>
            <div class="text-end">
              <div class="text-secondary x-small mb-1 opacity-75 fw-medium">{{ item.time }}</div>
              <span class="badge rounded-pill px-3 py-1 fw-bold" [class]="getStatusClass(item.status)" style="font-size: 0.65rem; letter-spacing: 0.05em;">
                {{ item.status | uppercase }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  `
})
export class AdminActivity {
  activity = signal([
    { type: 'bid', message: 'New high bid on Luxury Villa #12', user: 'BidderXYZ', target: 'Auction #402', time: 'Just now', status: 'pending' },
    { type: 'user', message: 'User verification submitted', user: 'Anil Kumar', target: 'KYC Document', time: '12 mins ago', status: 'awaiting_review' },
    { type: 'auction', message: 'Auction ended with no bids', user: 'System', target: 'Auction #403', time: '1 hr ago', status: 'completed' },
    { type: 'report', message: 'Comment reported for moderation', user: 'SafetyBot', target: 'User #1044', time: '2 hrs ago', status: 'flagged' }
  ]);

  getIcon(type: string): string {
    switch (type) {
      case 'bid': return 'fas fa-gavel';
      case 'user': return 'fas fa-user-shield';
      case 'auction': return 'fas fa-box-open';
      default: return 'fas fa-info-circle';
    }
  }

  getStatusClass(status: string): string {
    if (status === 'completed' || status === 'pending') return 'bg-success bg-opacity-10 text-success';
    if (status === 'awaiting_review') return 'bg-warning bg-opacity-10 text-warning';
    return 'bg-danger bg-opacity-10 text-danger';
  }
}
