import { Component, OnInit, inject, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterLink } from '@angular/router';
import { DashboardService, UserBid } from '../../../core/services/dashboard.service';

@Component({
  selector: 'app-my-bids',
  standalone: true,
  imports: [CommonModule, RouterLink],
  templateUrl: './my-bids.html',
})
export class MyBids implements OnInit {
  private dashboardService = inject(DashboardService);

  bids = signal<UserBid[]>([]);
  isLoading = signal(true);
  errorMessage = signal<string | null>(null);

  ngOnInit(): void {
    this.loadBids();
  }

  loadBids(): void {
    this.isLoading.set(true);
    this.errorMessage.set(null);

    this.dashboardService.getMyBids().subscribe({
      next: (data) => {
        this.bids.set(data);
        this.isLoading.set(false);
      },
      error: (err: Error) => {
        this.errorMessage.set(err.message);
        this.isLoading.set(false);
      }
    });
  }

  getStatusClass(status: string): string {
    const map: Record<string, string> = {
      winning: 'bg-success',
      outbid: 'bg-danger',
      closed: 'bg-secondary',
    };
    return map[status] ?? 'bg-secondary';
  }
}
