import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-dashboard-overview',
  standalone: true,
  imports: [CommonModule],
  template: `
    <div class="glass-panel p-5 h-100">
      <h2 class="display-font h3 mb-4">Account Overview</h2>
      
      <div class="row g-4 mb-5">
        <div class="col-md-4" *ngFor="let stat of stats">
          <div class="p-4 rounded-3 border border-white border-opacity-10 bg-white bg-opacity-5">
            <small class="text-secondary d-block mb-1 text-uppercase fw-bold" style="font-size: 0.7rem;">{{ stat.label }}</small>
            <span class="h2 mb-0 fw-bold text-white">{{ stat.value }}</span>
          </div>
        </div>
      </div>

      <div class="p-4 rounded-3 border border-white border-opacity-10">
        <h5 class="h6 text-white mb-3">Recent Activity</h5>
        <p class="text-secondary small">You haven't participated in any auctions yet. Start browsing to place your first bid!</p>
        <button class="btn btn-outline-gold mt-2 btn-sm">Browse Auctions</button>
      </div>
    </div>
  `
})
export class Overview {
  stats = [
    { label: 'Active Bids', value: '0' },
    { label: 'Winning High Bids', value: '0' },
    { label: 'Total Auctions Participated', value: '0' }
  ];
}
