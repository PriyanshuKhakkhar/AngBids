import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-about-mission-values',
  imports: [CommonModule],
  templateUrl: './about-mission-values.html',
  styleUrl: './about-mission-values.css',
  standalone: true
})
export class AboutMissionValues {
  aboutCards = [
    {
      title: 'Our Mission',
      iconClass: 'fas fa-bullseye',
      description: 'To completely democratize access to high-value assets by providing a transparent, elite, and frictionless platform.',
      colorClass: 'text-primary'
    },
    {
      title: 'Our Vision',
      iconClass: 'fas fa-eye',
      description: 'To become the gold standard and premier destination worldwide for verifying and trading extraordinary collectibles.',
      colorClass: 'text-warning'
    },
    {
      title: 'Our Values',
      iconClass: 'fas fa-heart',
      description: 'Total transparency, unshakable security, rigorous elite curation, and delivering the highest quality user experience.',
      colorClass: 'text-success'
    }
  ];
}
