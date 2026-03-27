import { Component, Input } from '@angular/core';
import { RouterLink } from '@angular/router';
import { CommonModule } from '@angular/common';
import { CountdownTimer } from '../countdown-timer/countdown-timer';
import { Auction } from '../../../core/models/auction.model';

@Component({
  selector: 'app-auction-card',
  standalone: true,
  imports: [RouterLink, CountdownTimer, CommonModule],
  templateUrl: './auction-card.html',
  styleUrl: './auction-card.css',
})
export class AuctionCard {
  @Input({ required: true }) auction!: Auction;
}
