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

  /** Minimum valid bid: current bid + minimum increment */
  minBid = computed(() => {
    const current = Number(this.auction()?.currentBid || 0);
    const minInc = Number(this.auction()?.minIncrement || 1);
    return current > 0 ? current + minInc : minInc;
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
        if (data.bids) {
            this.bids.set(data.bids);
        }
        
        // Set dynamic min validator based on current bid
        this.bidControl.setValidators([
          Validators.required,
          Validators.min(this.minBid()),
        ]);
        this.bidControl.updateValueAndValidity();
        
        // Disable bidding if auction has ended
        if (new Date(data.endDate) <= new Date() || data.status === 'closed') {
            this.bidControl.disable();
        } else {
            this.bidControl.enable();
        }
        
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
        // Refresh auction to get updated current bid and bids list
        this.loadAuction(auctionId);
      },
      error: (err: Error) => {
        this.bidError.set(err.message);
        this.isBidding.set(false);
      }
    });
  }
}
