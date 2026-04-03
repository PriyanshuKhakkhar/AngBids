import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterLink } from '@angular/router';

@Component({
  selector: 'app-watchlist',
  standalone: true,
  imports: [CommonModule, RouterLink],
  template: `
    <div class="glass-panel p-5 h-100">
      <h2 class="display-font h3 mb-4">Watchlist</h2>
      <div class="text-center py-5">
        <p class="text-secondary">Your watchlist is empty. Save auctions you're interested in.</p>
        <a routerLink="/auctions" class="btn btn-outline-gold btn-sm mt-2">Browse Auctions</a>
      </div>
    </div>
  `
})
export class Watchlist {}
