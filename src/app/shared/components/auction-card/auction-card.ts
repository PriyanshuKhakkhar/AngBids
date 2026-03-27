import { Component, Input } from '@angular/core';
import { RouterLink } from '@angular/router';
import { CountdownTimer } from '../countdown-timer/countdown-timer';

export interface Auction {
  id: number;
  title: string;
  description: string;
  imageUrl: string;
  currentBid: string;
  endDate: string | Date; // Added for the new timer
  buttonText: string;
}

@Component({
  selector: 'app-auction-card',
  imports: [RouterLink, CountdownTimer],
  templateUrl: './auction-card.html',
  styleUrl: './auction-card.css',
})
export class AuctionCard {
  @Input({ required: true }) auction!: Auction;
}
