import { Component, Input } from '@angular/core';
import { RouterLink } from '@angular/router';
import { CountdownTimer } from '../countdown-timer/countdown-timer';
import { Auction } from '../../../core/models/auction.model';

@Component({
  selector: 'app-auction-card',
  imports: [RouterLink, CountdownTimer],
  templateUrl: './auction-card.html',
  styleUrl: './auction-card.css',
})
export class AuctionCard {
  @Input({ required: true }) auction!: Auction;
}
