import { Component } from '@angular/core';
import { RouterOutlet, RouterLink, RouterLinkActive } from '@angular/router';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-dashboard',
  standalone: true,
  imports: [RouterOutlet, RouterLink, RouterLinkActive, CommonModule],
  templateUrl: './dashboard.html',
  styleUrl: './dashboard.css'
})
export class Dashboard {
  menuItems = [
    { label: 'Overview', icon: 'fas fa-th-large', link: '/dashboard' },
    { label: 'My Bids', icon: 'fas fa-gavel', link: '/dashboard/bids' },
    { label: 'Watchlist', icon: 'fas fa-heart', link: '/dashboard/watchlist' },
    { label: 'My Listings', icon: 'fas fa-plus-circle', link: '/dashboard/my-listings' },
    { label: 'Profile Settings', icon: 'fas fa-user-cog', link: '/dashboard/profile' },
  ];
}
