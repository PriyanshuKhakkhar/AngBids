import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-about-journey',
  imports: [CommonModule],
  templateUrl: './about-journey.html',
  styleUrl: './about-journey.css',
  standalone: true
})
export class AboutJourney {
  journeyFeaturesCol1 = [
    { text: 'Verified Sellers', icon: 'fas fa-check-circle' },
    { text: 'Secure Payments', icon: 'fas fa-check-circle' }
  ];
  journeyFeaturesCol2 = [
    { text: 'Global Shipping', icon: 'fas fa-check-circle' },
    { text: '24/7 Expert Support', icon: 'fas fa-check-circle' }
  ];
}
