import { Component, inject } from '@angular/core';
import { RouterLink, ActivatedRoute } from '@angular/router';
import { Auction, Bid } from '../../../core/models/auction.model';
import { CountdownTimer } from '../../../shared/components/countdown-timer/countdown-timer';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-auction-details',
  templateUrl: './auction-details.html',
  standalone: true,
  imports: [RouterLink, CountdownTimer, CommonModule],
})
export class AuctionDetails {
  private route = inject(ActivatedRoute);

  // Mock auction data
  auction: Auction = {
    id: 1,
    title: 'Vintage Chronograph Masterpiece',
    description: 'A timeless addition to any collection. Featuring original parts, meticulous craftsmanship, and a verified heritage certificate.',
    imageUrl: 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=1200',
    currentBid: 45200,
    startingPrice: 40000,
    endDate: new Date(Date.now() + 1000 * 60 * 60 * 48), // 2 days from now
    category: 'Watches',
    status: 'active'
  };

  // Mock bid history
  bids: Bid[] = [
    { id: 1, auctionId: 1, amount: 45200, bidderName: 'Bidder ID #402', time: new Date() },
    { id: 2, auctionId: 1, amount: 44800, bidderName: 'Bidder ID #011', time: new Date(Date.now() - 1000 * 60 * 15) },
  ];
}
