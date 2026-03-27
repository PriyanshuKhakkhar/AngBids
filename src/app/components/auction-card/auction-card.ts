import { Component, Input } from '@angular/core';
import { RouterLink } from '@angular/router';

export interface Auction {
  id: number;
  title: string;
  description: string;
  imageUrl: string;
  currentBid: string;
  days: string;
  hours: string;
  minutes: string;
  seconds: string;
  buttonText: string;
}

@Component({
  selector: 'app-auction-card',
  imports: [RouterLink],
  templateUrl: './auction-card.html',
  styleUrl: './auction-card.css',
})
export class AuctionCard {
  @Input({ required: true }) auction!: Auction;
}
