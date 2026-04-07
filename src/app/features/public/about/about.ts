import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';

import { AboutHero } from './components/about-hero/about-hero';
import { AboutFeatures } from './components/about-features/about-features';
import { AboutMissionValues } from './components/about-mission-values/about-mission-values';
import { AboutJourney } from './components/about-journey/about-journey';
import { AboutStats } from './components/about-stats/about-stats';
import { AboutCta } from './components/about-cta/about-cta';

@Component({
  selector: 'app-about',
  templateUrl: './about.html',
  styleUrl: './about.css',
  standalone: true,
  imports: [
    CommonModule,
    RouterModule,
    AboutHero,
    AboutFeatures,
    AboutMissionValues,
    AboutJourney,
    AboutStats,
    AboutCta
  ],
})
export class About {
  // Master container component connecting independent sections
}
