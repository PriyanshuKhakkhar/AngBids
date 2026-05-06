import { Component, inject, OnInit, OnDestroy, signal, computed } from '@angular/core';
import { RouterLink, ActivatedRoute, Router } from '@angular/router';
import { CommonModule } from '@angular/common';
import { ReactiveFormsModule, FormControl, Validators } from '@angular/forms';
import { Auction, Bid } from '../../../core/models/auction.model';
import { AuctionService } from '../../../core/services/auction.service';
import { AuthService } from '../../../core/services/auth.service';
import { CountdownTimer } from '../../../shared/components/countdown-timer/countdown-timer';
import { Subscription } from 'rxjs';

@Component({
  selector: 'app-auction-details',
  templateUrl: './auction-details.html',
  standalone: true,
  imports: [RouterLink, CountdownTimer, CommonModule, ReactiveFormsModule],
})
export class AuctionDetails implements OnInit, OnDestroy {
  private route = inject(ActivatedRoute);
  private router = inject(Router);
  private auctionService = inject(AuctionService);
  private authService = inject(AuthService);

  // ─── State Signals ────────────────────────────────────────────
  auction       = signal<Auction | null>(null);
  bids          = signal<Bid[]>([]);
  isLoading     = signal(true);
  errorMessage  = signal<string | null>(null);

  // Bid feedback
  bidSuccess    = signal<string | null>(null);
  bidError      = signal<string | null>(null);
  isBidding     = signal(false);

  // Auth / closed state feedback
  authMessage   = signal<string | null>(null);
  isAuctionClosed = signal(false);

  // ─── Computed Values ──────────────────────────────────────────

  /** Minimum valid bid amount = current bid + min increment */
  minBid = computed(() => {
    const current = Number(this.auction()?.currentBid || 0);
    const inc     = Number(this.auction()?.minIncrement || 1);
    return current > 0 ? current + inc : inc;
  });

  /** Derived auction status: 'Live' | 'Upcoming' | 'Closed' */
  auctionStatus = computed((): 'Live' | 'Upcoming' | 'Closed' => {
    const a = this.auction();
    if (!a) return 'Closed';
    const now   = new Date();
    const end   = new Date(a.endDate);
    // startDate isn't in the model yet, fall back to status field
    if (a.status === 'closed' || end <= now) return 'Closed';
    if (a.status === 'pending') return 'Upcoming';
    return 'Live';
  });
  
  /** True if user is verified to bid */
  isKycApproved = computed(() => this.authService.isKycApproved());

  // ─── Reactive Form ────────────────────────────────────────────
  bidControl = new FormControl<number | null>(null, [Validators.required, Validators.min(1)]);

  private routeSub?: Subscription;

  // ─── Lifecycle ────────────────────────────────────────────────
  ngOnInit(): void {
    this.routeSub = this.route.params.subscribe(params => {
      this.loadAuction(params['id']);
    });
  }

  ngOnDestroy(): void {
    this.routeSub?.unsubscribe();
  }

  // ─── Public Helpers ───────────────────────────────────────────

  /** Returns true if the current user is authenticated */
  isLoggedIn(): boolean {
    return this.authService.isLoggedIn();
  }

  /** Navigate back to the auctions listing */
  goBack(): void {
    this.router.navigate(['/auctions']);
  }

  // ─── Data Loading ─────────────────────────────────────────────

  loadAuction(id: string | number): void {
    this.isLoading.set(true);
    this.errorMessage.set(null);
    this.bidSuccess.set(null);
    this.bidError.set(null);

    this.auctionService.getAuctionById(id).subscribe({
      next: (data) => {
        this.auction.set(data);
        this.bids.set(data.bids ?? []);
        this._applyAuctionState(data);
        this.isLoading.set(false);
      },
      error: (err: Error) => {
        this.errorMessage.set(err.message);
        this.isLoading.set(false);
      }
    });
  }

  // ─── Bid Placement ────────────────────────────────────────────

  placeBid(): void {
    // 1. Auth guard
    if (!this.authService.isLoggedIn()) {
      this.authMessage.set('Please login to place a bid.');
      setTimeout(() => this.router.navigate(['/login']), 1500);
      return;
    }

    // 1.5 KYC guard
    if (!this.isKycApproved()) {
      this.bidError.set('Complete identity verification to participate in auctions.');
      setTimeout(() => this.router.navigate(['/dashboard/verification']), 2000);
      return;
    }

    // 2. Auction closed guard
    if (this.isAuctionClosed()) {
      this.bidError.set('This auction has ended. Bidding is no longer allowed.');
      return;
    }

    // 3. Form validity
    if (this.bidControl.invalid || !this.auction()) {
      this.bidControl.markAsTouched();
      return;
    }

    const amount     = this.bidControl.value!;
    const currentBid = Number(this.auction()!.currentBid || 0);

    // 4. Manual amount validation: must exceed current bid
    if (amount <= currentBid) {
      this.bidError.set(`Your bid must be higher than the current bid (${this._formatCurrency(currentBid)}).`);
      return;
    }

    // 5. Must meet minimum increment
    if (amount < this.minBid()) {
      this.bidError.set(`Minimum bid is ${this._formatCurrency(this.minBid())}.`);
      return;
    }

    // 6. Submit bid
    this.isBidding.set(true);
    this.bidError.set(null);
    this.bidSuccess.set(null);

    const auctionId = this.auction()!.id;

    this.auctionService.placeBid(auctionId, amount).subscribe({
      next: (res) => {
        // Optimistic UI: update current bid immediately
        this.auction.update(a => a ? { ...a, currentBid: amount, totalBids: (a.totalBids ?? 0) + 1 } : a);
        // Prepend to bid history
        this.bids.update(prev => [res.bid, ...prev]);
        this.bidSuccess.set(res.message || 'Bid placed successfully!');
        this.bidControl.reset();
        this.isBidding.set(false);

        // Auto-clear success after 5s, then reload latest server state
        setTimeout(() => {
          this.bidSuccess.set(null);
          this.loadAuction(auctionId);
        }, 5000);
      },
      error: (err: Error) => {
        this.bidError.set(err.message || 'Failed to place bid. Please try again.');
        this.isBidding.set(false);
      }
    });
  }

  // ─── Private Helpers ──────────────────────────────────────────

  /**
   * Updates the form control validators and closed state
   * based on the loaded auction data.
   */
  private _applyAuctionState(data: Auction): void {
    const endDate = new Date(data.endDate);
    const isClosed = endDate <= new Date() || data.status === 'closed';
    this.isAuctionClosed.set(isClosed);

    // Update validators with real minimum bid
    this.bidControl.setValidators([
      Validators.required,
      Validators.min(this.minBid()),
    ]);
    this.bidControl.updateValueAndValidity();

    if (isClosed || !this.isKycApproved()) {
      this.bidControl.disable();
    } else {
      this.bidControl.enable();
    }
  }

  /** Simple currency formatter for inline error messages */
  private _formatCurrency(value: number): string {
    return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(value);
  }
}
