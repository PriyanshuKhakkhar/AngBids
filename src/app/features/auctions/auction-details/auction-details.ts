import { Component, inject, OnInit, signal, computed } from '@angular/core';
import { RouterLink, ActivatedRoute } from '@angular/router';
import { CommonModule } from '@angular/common';
import { ReactiveFormsModule, FormControl, Validators } from '@angular/forms';
import { Auction, Bid } from '../../../core/models/auction.model';
import { AuctionService } from '../../../core/services/auction.service';
import { CountdownTimer } from '../../../shared/components/countdown-timer/countdown-timer';

@Component({
  selector: 'app-auction-details',
  templateUrl: './auction-details.html',
  standalone: true,
  imports: [RouterLink, CountdownTimer, CommonModule, ReactiveFormsModule],
})
export class AuctionDetails implements OnInit {
  private route = inject(ActivatedRoute);
  private auctionService = inject(AuctionService);

  auction = signal<Auction | null>(null);
  bids = signal<Bid[]>([]);
  isLoading = signal(true);
  errorMessage = signal<string | null>(null);
  bidSuccess = signal<string | null>(null);
  bidError = signal<string | null>(null);
  isBidding = signal(false);

  /** Minimum valid bid: 1 above current bid */
  minBid = computed(() => {
    const current = this.auction()?.currentBid;
    return current ? Number(current) + 1 : 1;
  });

  bidControl = new FormControl<number | null>(null, [
    Validators.required,
    Validators.min(1),
  ]);

  ngOnInit(): void {
    const id = this.route.snapshot.params['id'];
    this.loadAuction(id);
  }

  loadAuction(id: string | number): void {
    this.isLoading.set(true);
    this.errorMessage.set(null);

    this.auctionService.getAuctionById(id).subscribe({
      next: (data) => {
        this.auction.set(data);
        // Set dynamic min validator based on current bid
        this.bidControl.setValidators([
          Validators.required,
          Validators.min(this.minBid()),
        ]);
        this.bidControl.updateValueAndValidity();
        this.isLoading.set(false);
      },
      error: (err: Error) => {
        this.errorMessage.set(err.message);
        this.isLoading.set(false);
      }
    });
  }

  placeBid(): void {
    if (this.bidControl.invalid || !this.auction()) return;

    this.isBidding.set(true);
    this.bidError.set(null);
    this.bidSuccess.set(null);

    const amount = this.bidControl.value!;
    const auctionId = this.auction()!.id;

    this.auctionService.placeBid(auctionId, amount).subscribe({
      next: (res) => {
        this.bidSuccess.set(res.message || 'Bid placed successfully!');
        this.bids.update(prev => [res.bid, ...prev]);
        this.bidControl.reset();
        this.isBidding.set(false);
        // Refresh auction to get updated current bid
        this.loadAuction(auctionId);
      },
      error: (err: Error) => {
        this.bidError.set(err.message);
        this.isBidding.set(false);
      }
    });
  }
}
