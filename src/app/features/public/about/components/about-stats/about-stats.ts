import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-about-stats',
  imports: [CommonModule],
  templateUrl: './about-stats.html',
  styleUrl: './about-stats.css',
  standalone: true
})
export class AboutStats {
  stats = [
    { value: '10K+', label: 'Active Users' },
    { value: '50K+', label: 'Auctions Closed' },
    { value: '₹10M+', label: 'Volume Traded' },
    { value: '99%', label: 'Happy Customers' }
  ];
}
