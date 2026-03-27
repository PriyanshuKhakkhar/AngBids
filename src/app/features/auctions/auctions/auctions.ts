import { Component } from '@angular/core';
import { AuctionCard, Auction } from '../../../shared/components/auction-card/auction-card';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-auctions',
  templateUrl: './auctions.html',
  standalone: true,
  imports: [AuctionCard, CommonModule],
})
export class Auctions {
  auctions: Auction[] = [
    {
      id: 2,
      title: 'Midnight Sapphire GT',
      description: 'V12 Twin-Turbo Heritage Edition',
      imageUrl: 'https://images.unsplash.com/photo-1542281286-9e0a16bb7366?auto=format&fit=crop&w=1200',
      currentBid: '$210,000',
      endDate: new Date(Date.now() + 1000 * 60 * 60 * 8),
      buttonText: 'Bid Now',
    },
    {
      id: 3,
      title: 'The Crystal Pavillion',
      description: '6-Bedroom Ultra-Modern Estate',
      imageUrl: 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1200',
      currentBid: '$3.45M',
      endDate: new Date(Date.now() + 1000 * 60 * 60 * 24 * 4),
      buttonText: 'Submit Bid',
    },
    {
      id: 1,
      title: 'Grand Complication Chronograph',
      description: 'Rare 18k Rose Gold Perpetual Calendar',
      imageUrl: 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=1200',
      currentBid: '$45,200',
      endDate: new Date(Date.now() + 1000 * 60 * 60 * 48),
      buttonText: 'Submit Bid',
    },
  ];
}
