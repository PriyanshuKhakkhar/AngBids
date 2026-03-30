import { Component, OnInit, inject, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterLink } from '@angular/router';
import { DashboardService, DashboardStats, RecentActivity } from '../../../core/services/dashboard.service';

@Component({
  selector: 'app-dashboard-overview',
  standalone: true,
  imports: [CommonModule, RouterLink],
  templateUrl: './overview.html',
})
export class Overview implements OnInit {
  private dashboardService = inject(DashboardService);

  isLoading = signal(true);
  errorMessage = signal<string | null>(null);
  stats = signal<DashboardStats | null>(null);
  recentActivity = signal<RecentActivity[]>([]);

  get statsArray() {
    const s = this.stats();
    if (!s) return [];
    return [
      { label: 'Active Bids', value: s.active_bids },
      { label: 'Winning High Bids', value: s.winning_bids },
      { label: 'Total Auctions Participated', value: s.total_participated },
    ];
  }

  ngOnInit(): void {
    this.dashboardService.getDashboardData().subscribe({
      next: (data) => {
        this.stats.set(data.stats);
        this.recentActivity.set(data.recent_activity);
        this.isLoading.set(false);
      },
      error: (err: Error) => {
        this.errorMessage.set(err.message);
        this.isLoading.set(false);
      }
    });
  }
}
