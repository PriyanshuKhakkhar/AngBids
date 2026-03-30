import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
@Component({
  selector: 'app-about-features',
  imports: [CommonModule],
  templateUrl: './about-features.html',
  styleUrl: './about-features.css',
})
export class AboutFeatures {
  features = [
    {
      title: 'Real-Time Bidding',
      iconClass: 'fas fa-gavel',
      colorClass: 'text-primary',
      description: 'Experience the adrenaline of live auctions with zero latency. Our websocket technology ensures you never miss a bid.'
    },
    {
      title: 'Buyer Protection',
      iconClass: 'fas fa-shield-alt',
      colorClass: 'text-warning',
      description: 'Your funds are held in escrow until you receive your item as described. We mediate all disputes fairly.'
    },
    {
      title: 'Market Insights',
      iconClass: 'fas fa-chart-line',
      colorClass: 'text-success',
      description: 'Get access to historical pricing data and trends to make informed decisions on your investments.'
    },
    {
      title: 'Mobile Optimized',
      iconClass: 'fas fa-mobile-alt',
      colorClass: 'text-danger',
      description: 'Bid on the go with our fully responsive design. Manage your watchlist and bids from anywhere.'
    },
    {
      title: 'VIP Program',
      iconClass: 'fas fa-star',
      colorClass: 'text-info',
      description: 'Exclusive access to high-value auctions and lower fees for our most active members.'
    },
    {
      title: 'Community Forums',
      iconClass: 'fas fa-comments',
      colorClass: 'text-secondary',
      styleOverride: 'color: #6f42c1 !important;',
      description: 'Connect with other collectors, discuss items, and share your finds in our active community.'
    }
  ];
}
