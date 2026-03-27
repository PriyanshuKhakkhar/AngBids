import { Component, inject, OnInit, signal } from '@angular/core';
import { RouterLink, ActivatedRoute } from '@angular/router';
import { Auction, Bid } from '../../../core/models/auction.model';
import { AuctionService } from '../../../core/services/auction.service';
import { CountdownTimer } from '../../../shared/components/countdown-timer/countdown-timer';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-auction-details',
  templateUrl: './auction-details.html',
  standalone: true,
  imports: [RouterLink, CountdownTimer, CommonModule],
})
export class AuctionDetails implements OnInit {
  private route = inject(ActivatedRoute);
  private auctionService = inject(AuctionService);

  // States
  auction = signal<Auction | null>(null);
  isLoading = signal(true);

  // Mock bid history (could also be fetched via a dedicated service later)
  bids: Bid[] = [];

  ngOnInit(): void {
    const id = this.route.snapshot.params['id'];
    this.loadAuction(id);
  }

  loadAuction(id: string | number): void {
    this.isLoading.set(true);
    this.auctionService.getAuctionById(id).subscribe({
      next: (data) => {
        this.auction.set(data);
        this.isLoading.set(false);
      },
      error: () => this.isLoading.set(false)
    });
  }
}
