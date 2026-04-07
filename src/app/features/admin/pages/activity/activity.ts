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
        <div *ngFor="let item of activity()" class="list-group-item bg-transparent border-opacity-10 border-white py-4 px-0">
          <div class="d-flex justify-content-between align-items-start">
            <div class="d-flex gap-3">
              <div class="activity-icon flex-shrink-0">
                <span class="badge bg-gold-soft text-gold px-3 py-2 rounded-3">
                  <i [class]="getIcon(item.type)"></i>
                </span>
              </div>
              <div>
                <div class="fw-bold small text-light">{{ item.message }}</div>
                <div class="text-secondary x-small mt-1 d-flex align-items-center gap-3">
                  <span><i class="fas fa-user-circle me-1"></i> {{ item.user }}</span>
                  <span><i class="fas fa-link me-1"></i> {{ item.target }}</span>
                </div>
              </div>
            </div>
            <div class="text-end">
              <div class="text-secondary x-small mb-1">{{ item.time }}</div>
              <span class="badge" [class]="getStatusClass(item.status)" style="font-size: 0.65rem;">
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
    if (status === 'completed' || status === 'pending') return 'bg-success-soft text-success';
    if (status === 'awaiting_review') return 'bg-warning-soft text-warning';
    return 'bg-danger-soft text-danger';
  }
}
