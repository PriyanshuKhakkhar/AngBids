import { Component } from '@angular/core';
import { RouterLink } from '@angular/router';

@Component({
  selector: 'app-auction-details',
  templateUrl: './auction-details.html',
  standalone: true,
  imports: [RouterLink],
})
export class AuctionDetails {}
