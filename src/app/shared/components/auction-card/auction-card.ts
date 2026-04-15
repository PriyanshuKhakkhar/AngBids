import { Component, Input, inject } from '@angular/core';
import { RouterLink, Router } from '@angular/router';
import { CommonModule } from '@angular/common';
import { CountdownTimer } from '../countdown-timer/countdown-timer';
import { Auction } from '../../../core/models/auction.model';
import { AuthService } from '../../../core/services/auth.service';

@Component({
  selector: 'app-auction-card',
  standalone: true,
  imports: [RouterLink, CountdownTimer, CommonModule],
  templateUrl: './auction-card.html',
  styleUrl: './auction-card.css',
})
export class AuctionCard {
  @Input({ required: true }) auction!: Auction;
  placeholder = 'assets/images/banner-3.png';

  private authService = inject(AuthService);
  private router = inject(Router);

  /** Returns true if the current user is authenticated */
  isLoggedIn(): boolean {
    return this.authService.isLoggedIn();
  }

  isFavourite(id: number): boolean {
    const favs = JSON.parse(localStorage.getItem('favourite_auctions') || '[]');
    return favs.includes(id);
  }

  toggleFavourite(id: number): void {
    let favs = JSON.parse(localStorage.getItem('favourite_auctions') || '[]');
    if (favs.includes(id)) {
      favs = favs.filter((favId: number) => favId !== id);
    } else {
      favs.push(id);
    }
    localStorage.setItem('favourite_auctions', JSON.stringify(favs));
  }

  /**
   * Handles BID NOW click.
   * Always navigates to the detail page — auth is enforced
   * on the detail page's bid form, so guests can still see the auction.
   * The event stops propagation to prevent double-navigation from stretched-link.
   */
  handleBidClick(event: Event): void {
    event.preventDefault();
    event.stopPropagation();
    this.router.navigate(['/auctions', this.auction.id]);
  }
}
