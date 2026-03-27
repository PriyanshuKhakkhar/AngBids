import { Component, OnInit, inject, signal } from '@angular/core';
import { AuctionCard } from '../../../shared/components/auction-card/auction-card';
import { Auction } from '../../../core/models/auction.model';
import { AuctionService } from '../../../core/services/auction.service';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-auctions',
  templateUrl: './auctions.html',
  standalone: true,
  imports: [AuctionCard, CommonModule],
})
export class Auctions implements OnInit {
  private auctionService = inject(AuctionService);

  // State signals
  auctions = signal<Auction[]>([]);
  isLoading = signal<boolean>(true);
  errorMessage = signal<string | null>(null);

  ngOnInit(): void {
    this.fetchAuctions();
  }

  /**
   * Fetch data from the Laravel API
   */
  fetchAuctions(): void {
    this.isLoading.set(true);
    this.auctionService.getAuctions().subscribe({
      next: (data) => {
        this.auctions.set(data);
        this.isLoading.set(false);
      },
      error: (err) => {
        this.errorMessage.set(err.message);
        this.isLoading.set(false);
      }
    });
  }
}
