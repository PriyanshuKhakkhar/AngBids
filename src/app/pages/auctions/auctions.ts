import { Component } from '@angular/core';
import { RouterLink } from '@angular/router';

@Component({
  selector: 'app-auctions',
  templateUrl: './auctions.html',
  standalone: true,
  imports: [RouterLink],
})
export class Auctions {}
