import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-contact-info',
  imports: [CommonModule],
  templateUrl: './contact-info.html',
  styleUrl: './contact-info.css',
  standalone: true
})
export class ContactInfo {
  contactDetails = [
    {
      icon: 'fas fa-map-marker-alt',
      title: 'Our Office',
      info: '123 Elite Avenue, Global Central, World 54321',
      color: 'text-primary'
    },
    {
      icon: 'fas fa-phone-alt',
      title: 'Phone Number',
      info: '+1 (800) 555-BIDS (2437)',
      color: 'text-success'
    },
    {
      icon: 'fas fa-envelope',
      title: 'Email Address',
      info: 'support@angbids.com',
      color: 'text-warning'
    },
    {
      icon: 'fas fa-clock',
      title: 'Business Hours',
      info: 'Monday - Friday (9:00 AM - 8:00 PM EST)',
      color: 'text-info'
    },
    {
      icon: 'fas fa-bolt',
      title: 'Response Time',
      info: 'Verified Tier guarantees a sub-2 hour resolution.',
      color: 'text-danger'
    }
  ];
}
