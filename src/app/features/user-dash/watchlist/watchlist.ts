import { Component, OnInit, inject, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterLink } from '@angular/router';
import { DashboardService } from '../../../core/services/dashboard.service';
import { Auction } from '../../../core/models/auction.model';
import { AuctionCard } from '../../../shared/components/auction-card/auction-card';

@Component({
  selector: 'app-watchlist',
  standalone: true,
  imports: [CommonModule, RouterLink, AuctionCard],
  template: `
    <div class="glass-panel p-5 h-100">
      <h2 class="display-font h3 mb-4">Watchlist</h2>

      <div *ngIf="isLoading()" class="text-center py-5">
        <div class="spinner-border text-gold" role="status"></div>
        <p class="text-secondary mt-3 small">Loading your tracked auctions...</p>
      </div>

      <div *ngIf="errorMessage()" class="alert alert-danger bg-transparent border-danger text-danger small mb-4">
          <i class="fas fa-exclamation-triangle me-2"></i>{{ errorMessage() }}
      </div>
      
      <div *ngIf="!isLoading() && !errorMessage() && watchlist().length === 0" class="text-center py-5">
        <div class="mb-3"><i class="fas fa-heart-broken fa-3x text-secondary opacity-50"></i></div>
        <p class="text-secondary fw-semibold">Your watchlist is currently empty.</p>
        <p class="text-secondary small">Save auctions you're interested in while browsing.</p>
        <a routerLink="/auctions" class="btn btn-outline-gold btn-sm mt-3 px-4">Browse Active Auctions</a>
      </div>

      <div *ngIf="!isLoading() && !errorMessage() && watchlist().length > 0" class="row g-4">
        <div class="col-12 col-md-6 col-xl-4" *ngFor="let ai of watchlist()">
          <app-auction-card [auction]="ai"></app-auction-card>
        </div>
      </div>
    </div>
  `
})
export class Watchlist implements OnInit {
  private dashService = inject(DashboardService);
  
  watchlist = signal<Auction[]>([]);
  isLoading = signal(true);
  errorMessage = signal<string | null>(null);

  ngOnInit() {
    this.dashService.getWatchlist().subscribe({
      next: (data) => {
        this.watchlist.set(data);
        this.isLoading.set(false);
      },
      error: (err) => {
        this.errorMessage.set(err.message);
        this.isLoading.set(false);
      }
    });
  }
}
