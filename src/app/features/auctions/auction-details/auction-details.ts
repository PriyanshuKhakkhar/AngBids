import { Component, inject, OnInit, OnDestroy, signal, computed } from '@angular/core';
import { RouterLink, ActivatedRoute, Router } from '@angular/router';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule, FormControl, Validators } from '@angular/forms';
import { Auction, Bid } from '../../../core/models/auction.model';
import { AuctionService } from '../../../core/services/auction.service';
import { AuthService } from '../../../core/services/auth.service';
import { CountdownTimer } from '../../../shared/components/countdown-timer/countdown-timer';
import { Subscription } from 'rxjs';

@Component({
  selector: 'app-auction-details',
  templateUrl: './auction-details.html',
  standalone: true,
  imports: [RouterLink, CountdownTimer, CommonModule, FormsModule, ReactiveFormsModule],
  styles: [`
    .admin-control-panel {
        background: rgba(255, 255, 255, 0.03);
        border-radius: 16px;
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }
    .admin-btn {
        transition: all 0.2s ease;
        border-width: 1px;
        font-weight: 600;
        letter-spacing: 0.02em;
        text-transform: uppercase;
        font-size: 0.7rem;
    }
    .admin-btn:hover:not(:disabled) {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .admin-btn-approve:hover { background: #198754; color: white; border-color: #198754; }
    .admin-btn-pause:hover { background: #ffc107; color: black; border-color: #ffc107; }
    .admin-btn-close:hover { background: #0dcaf0; color: black; border-color: #0dcaf0; }
    .admin-btn-cancel:hover { background: #dc3545; color: white; border-color: #dc3545; }
    
    .admin-reason-input {
        background: #fff;
        border: 1px solid #dee2e6;
        color: #333;
        font-size: 0.85rem;
        transition: all 0.2s;
    }
    .admin-reason-input:focus {
        border-color: #d4af37;
        box-shadow: 0 0 0 0.2rem rgba(212, 175, 55, 0.1);
        outline: none;
    }
    .x-small { font-size: 0.65rem; }
  `]
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

  /** Derived auction status: 'Live' | 'Upcoming' | 'Closed' | 'Cancelled' */
  auctionStatus = computed((): 'Live' | 'Upcoming' | 'Closed' | 'Cancelled' => {
    const a = this.auction();
    if (!a) return 'Closed';
    
    if (a.status === 'cancelled') return 'Cancelled';
    
    const now   = new Date();
    const end   = new Date(a.endDate);
    
    if (a.status === 'closed' || a.status === 'ended' || end <= now) return 'Closed';
    if (a.status === 'pending') return 'Upcoming';
    return 'Live';
  });
  
  /** True if user is verified to bid */
  isKycApproved = computed(() => this.authService.isKycApproved());

  /** Administrative roles */
  isAdmin = computed(() => this.authService.isAdmin());
  isSuperAdmin = computed(() => this.authService.isSuperAdmin());

  /** Permission to manage: Creator OR Admin */
  canManage = computed(() => {
    const user = this.authService.currentUser();
    const auction = this.auction();
    if (!user || !auction) return false;
    
    // Check ownership (backend IDs might be string or number)
    const isOwner = Number(user.id) === Number(auction.user_id);
    return isOwner || this.isAdmin();
  });

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

  // ─── Management Actions ────────────────────────────────────────

  adminReason = signal<string>('');

  updateAuctionStatus(status: string): void {
    if (!this.canManage()) return;
    
    // Require reason for cancellation
    if (status === 'cancelled' && !this.adminReason().trim()) {
      this.bidError.set('A reason is required to cancel this auction.');
      return;
    }

    const id = this.auction()?.id;
    if (!id) return;

    this.isBidding.set(true); 
    this.auctionService.updateAuctionStatus(id, status, this.adminReason()).subscribe({
      next: (res) => {
        this.bidSuccess.set(`Auction status updated to ${status}`);
        this.adminReason.set('');
        this.loadAuction(id);
        this.isBidding.set(false);
      },
      error: (err) => {
        this.bidError.set(err.message);
        this.isBidding.set(false);
      }
    });
  }

  extendAuction(days: number): void {
    if (!this.canManage()) return;
    const auction = this.auction();
    if (!auction) return;

    const currentEnd = new Date(auction.endDate);
    currentEnd.setDate(currentEnd.getDate() + days);

    this.isBidding.set(true);
    this.auctionService.updateAuction(auction.id, { end_time: currentEnd.toISOString() }).subscribe({
      next: () => {
        this.bidSuccess.set(`Auction extended by ${days} days`);
        this.loadAuction(auction.id);
        this.isBidding.set(false);
      },
      error: (err) => {
        this.bidError.set(err.message);
        this.isBidding.set(false);
      }
    });
  }

  deleteAuction(): void {
    if (!this.canManage()) return;
    if (!confirm('Are you sure you want to permanently delete this auction? This cannot be undone.')) return;

    const id = this.auction()?.id;
    if (!id) return;

    this.auctionService.deleteAuction(id).subscribe({
      next: () => {
        alert('Auction deleted successfully');
        this.router.navigate(['/auctions']);
      },
      error: (err) => alert(err.message)
    });
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
