import { Component } from '@angular/core';
import { RouterLink } from '@angular/router';
import { AuctionCard, Auction } from '../../../shared/components/auction-card/auction-card';

export interface Category {
  name: string;
  icon: string;
}

export interface UpcomingAuction {
  title: string;
  description: string;
  date: string;
}

export interface Stat {
  value: string;
  label: string;
}

@Component({
  selector: 'app-home',
  imports: [RouterLink, AuctionCard],
  templateUrl: './home.html',
  styleUrl: './home.css',
})
export class Home {
  liveAuctions: Auction[] = [
    {
      id: 1,
      title: 'Grand Complication Chronograph',
      description: 'Rare 18k Rose Gold Perpetual Calendar',
      imageUrl: 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=1200',
      currentBid: '$45,200',
      endDate: new Date(Date.now() + 1000 * 60 * 60 * 48), // 2 days from now
      buttonText: 'Submit Bid',
    },
    {
      id: 2,
      title: 'Midnight Sapphire GT',
      description: 'V12 Twin-Turbo Heritage Edition',
      imageUrl: 'https://images.unsplash.com/photo-1542281286-9e0a16bb7366?auto=format&fit=crop&w=1200',
      currentBid: '$210,000',
      endDate: new Date(Date.now() + 1000 * 60 * 60 * 8), // 8 hours from now
      buttonText: 'Bid Now',
    },
    {
      id: 3,
      title: 'The Crystal Pavillion',
      description: '6-Bedroom Ultra-Modern Estate',
      imageUrl: 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1200',
      currentBid: '$3.45M',
      endDate: new Date(Date.now() + 1000 * 60 * 60 * 24 * 4), // 4 days from now
      buttonText: 'Submit Bid',
    },
  ];

  categories: Category[] = [
    { name: 'Electronics', icon: 'fas fa-laptop' },
    { name: 'Automotive', icon: 'fas fa-car' },
    { name: 'Luxury Jewelry', icon: 'fas fa-gem' },
    { name: 'Real Estate', icon: 'fas fa-home' },
  ];

  categoryPills = [
    { label: 'All Auctions', icon: '', active: true },
    { label: 'Electronics', icon: 'fas fa-laptop', active: false },
    { label: 'Watches', icon: 'fas fa-clock', active: false },
    { label: 'Vintage Cars', icon: 'fas fa-car', active: false },
    { label: 'Jewelry', icon: 'fas fa-gem', active: false },
  ];

  stats: Stat[] = [
    { value: '500+', label: 'Live Auctions' },
    { value: '$2.5M', label: 'Weekly Volume' },
    { value: '100k+', label: 'Verified Users' },
    { value: '99.9%', label: 'Success Rate' },
  ];

  upcomingAuctions: UpcomingAuction[] = [
    {
      title: 'Modernism in Kyoto',
      description: 'Architectural Masterpiece Collection',
      date: 'Feb 14, 2026',
    },
    {
      title: 'The Heritage Chronos',
      description: '50 Rare Timepieces from 1920-1950',
      date: 'Mar 02, 2026',
    },
    {
      title: 'Abstract Expressionism',
      description: 'Post-War American Art Auction',
      date: 'Mar 10, 2026',
    },
  ];

  partners = [
    { icon: 'fab fa-fedex' },
    { icon: 'fab fa-ups' },
    { icon: 'fab fa-dhl' },
    { icon: 'fab fa-stripe' },
    { icon: 'fab fa-apple-pay' },
  ];
}
