import { Component, OnInit, inject, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { RouterLink } from '@angular/router';
import { UserAuctionService, UserAuction } from '../../../core/services/user-auction.service';

@Component({
  selector: 'app-my-listings',
  standalone: true,
  imports: [CommonModule, FormsModule, RouterLink],
  template: `
    <div class="h-100">
      <!-- Header -->
      <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-center gap-3">
          <div class="p-3 rounded-circle bg-primary bg-opacity-10 text-primary">
            <i class="fas fa-store fs-5"></i>
          </div>
          <div>
            <h2 class="h4 fw-bold mb-0 text-dark">My Listings</h2>
            <p class="text-secondary small mb-0">Auctions you have submitted</p>
          </div>
        </div>
        <a routerLink="/dashboard/my-listings/create"
           class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
          <i class="fas fa-plus me-2"></i>New Auction
        </a>
      </div>

      <!-- Status Filter Tabs -->
      <div class="d-flex gap-2 mb-4 flex-wrap">
        <button *ngFor="let tab of statusTabs"
                class="btn btn-sm rounded-pill fw-semibold px-3"
                [class.btn-primary]="activeStatus === tab.value"
                [class.btn-outline-secondary]="activeStatus !== tab.value"
                (click)="setStatus(tab.value)">
          {{ tab.label }}
          <span *ngIf="tab.value === 'pending' && pendingCount > 0"
                class="badge bg-warning text-dark ms-1 rounded-pill">{{ pendingCount }}</span>
        </button>
      </div>

      <!-- Loading State -->
      <div *ngIf="isLoading()" class="text-center py-5">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
        <p class="text-secondary mt-3 small">Loading your listings...</p>
      </div>

      <!-- Error State -->
      <div *ngIf="errorMessage() && !isLoading()"
           class="alert alert-danger rounded-4 d-flex align-items-center gap-2 mb-4">
        <i class="fas fa-exclamation-triangle"></i>
        <span class="small">{{ errorMessage() }}</span>
        <button class="btn btn-link btn-sm text-danger ms-auto p-0 fw-bold" (click)="loadAuctions()">
          Retry
        </button>
      </div>

      <!-- Empty State -->
      <div *ngIf="!isLoading() && !errorMessage() && auctions().length === 0"
           class="text-center py-5">
        <div class="empty-icon mb-3">
          <div class="p-4 rounded-circle bg-secondary bg-opacity-10 d-inline-block">
            <i class="fas fa-store-slash fs-1 text-secondary opacity-50"></i>
          </div>
        </div>
        <h5 class="text-dark fw-bold mb-2">No listings found</h5>
        <p class="text-secondary small mb-4">
          <span *ngIf="activeStatus === 'all'">You haven't submitted any auctions yet.</span>
          <span *ngIf="activeStatus !== 'all'">No auctions with status "{{ activeStatus }}" found.</span>
        </p>
        <a routerLink="/dashboard/my-listings/create"
           class="btn btn-primary rounded-pill px-4 fw-bold">
          <i class="fas fa-plus me-2"></i>Create Your First Auction
        </a>
      </div>

      <!-- Listings Table -->
      <div *ngIf="!isLoading() && auctions().length > 0"
           class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="bg-light border-bottom">
              <tr>
                <th class="ps-4 py-3 text-secondary fw-bold small text-uppercase">Item</th>
                <th class="py-3 text-secondary fw-bold small text-uppercase">Category</th>
                <th class="py-3 text-secondary fw-bold small text-uppercase text-end">Starting Price</th>
                <th class="py-3 text-secondary fw-bold small text-uppercase text-center">Status</th>
                <th class="py-3 text-secondary fw-bold small text-uppercase">Submitted</th>
                <th class="pe-4 py-3 text-secondary fw-bold small text-uppercase text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr *ngFor="let auction of auctions()" class="border-bottom border-light">
                <td class="ps-4">
                  <div class="d-flex align-items-center gap-3">
                    <img [src]="auction.imageUrl"
                         class="rounded-3 flex-shrink-0 border"
                         width="52" height="52"
                         style="object-fit: cover;"
                         [alt]="auction.title"
                         (error)="onImgError($event)">
                    <div>
                      <div class="fw-semibold text-dark text-truncate"
                           style="max-width: 200px;"
                           [title]="auction.title">{{ auction.title }}</div>
                      <small class="text-muted">#{{ auction.id }} &middot; {{ auction.totalBids }} bids</small>
                    </div>
                  </div>
                </td>
                <td>
                  <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-2 small">
                    {{ auction.category }}
                  </span>
                </td>
                <td class="text-end font-monospace fw-semibold">
                  ₹{{ auction.startingPrice | number:'1.2-2' }}
                </td>
                <td class="text-center">
                  <span class="badge rounded-pill px-3 py-2"
                        [ngClass]="getStatusClass(auction.status)">
                    <i [class]="getStatusIcon(auction.status)" class="me-1"></i>
                    {{ getStatusLabel(auction.status) }}
                  </span>
                </td>
                <td>
                  <div class="small text-dark fw-semibold">{{ auction.createdAt | date:'dd MMM yyyy' }}</div>
                  <small class="text-muted">{{ auction.createdAt | date:'hh:mm a' }}</small>
                </td>
                <td class="pe-4 text-end">
                  <div class="d-flex gap-1 justify-content-end">
                    <!-- View button (only active/closed auctions are publicly visible) -->
                    <a *ngIf="auction.status === 'active' || auction.status === 'closed'"
                       [routerLink]="['/auctions', auction.id]"
                       class="btn btn-light btn-sm text-primary p-2"
                       title="View public listing">
                      <i class="fas fa-eye"></i>
                    </a>

                    <!-- Delete button (only pending auctions can be deleted) -->
                    <button *ngIf="auction.status === 'pending'"
                            class="btn btn-light btn-sm text-danger p-2"
                            title="Withdraw submission"
                            [disabled]="deletingId() === auction.id"
                            (click)="onDelete(auction)">
                      <span *ngIf="deletingId() !== auction.id"><i class="fas fa-trash-alt"></i></span>
                      <span *ngIf="deletingId() === auction.id">
                        <span class="spinner-border spinner-border-sm"></span>
                      </span>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div *ngIf="totalPages() > 1"
             class="d-flex justify-content-between align-items-center px-4 py-3 border-top bg-white">
          <small class="text-secondary">
            Page {{ currentPage() }} of {{ totalPages() }}
          </small>
          <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary btn-sm rounded-pill px-3"
                    [disabled]="currentPage() === 1"
                    (click)="changePage(currentPage() - 1)">
              <i class="fas fa-chevron-left"></i>
            </button>
            <button class="btn btn-outline-secondary btn-sm rounded-pill px-3"
                    [disabled]="currentPage() === totalPages()"
                    (click)="changePage(currentPage() + 1)">
              <i class="fas fa-chevron-right"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
  `,
  styles: [`
    .table-hover tbody tr:hover { background: rgba(13,110,253,0.02); }
    .btn-light:hover { transform: scale(1.08); transition: transform 0.15s; }
  `]
})
export class MyListings implements OnInit {
  private userAuctionService = inject(UserAuctionService);

  auctions = signal<UserAuction[]>([]);
  isLoading = signal(false);
  errorMessage = signal<string | null>(null);
  activeStatus = 'all';
  currentPage = signal(1);
  totalPages = signal(1);
  pendingCount = 0;
  deletingId = signal<number | null>(null);

  statusTabs = [
    { label: 'All Listings', value: 'all' },
    { label: 'Pending Review', value: 'pending' },
    { label: 'Approved', value: 'active' },
    { label: 'Closed', value: 'closed' },
    { label: 'Rejected', value: 'cancelled' },
  ];

  ngOnInit(): void {
    this.loadAuctions();
    this.loadPendingCount();
  }

  loadAuctions(): void {
    this.isLoading.set(true);
    this.errorMessage.set(null);

    this.userAuctionService.getMyAuctions({
      status: this.activeStatus,
      page: this.currentPage()
    }).subscribe({
      next: (res) => {
        this.auctions.set(res.data);
        this.totalPages.set(res.last_page);
        this.isLoading.set(false);
      },
      error: (err: Error) => {
        this.errorMessage.set(err.message);
        this.isLoading.set(false);
      }
    });
  }

  loadPendingCount(): void {
    this.userAuctionService.getMyAuctions({ status: 'pending' }).subscribe({
      next: (res) => {
        this.pendingCount = res.total;
      },
      error: () => { /* non-critical */ }
    });
  }

  setStatus(status: string): void {
    this.activeStatus = status;
    this.currentPage.set(1);
    this.loadAuctions();
  }

  changePage(page: number): void {
    this.currentPage.set(page);
    this.loadAuctions();
  }

  onDelete(auction: UserAuction): void {
    if (!confirm(`Are you sure you want to withdraw "${auction.title}"? This cannot be undone.`)) {
      return;
    }
    this.deletingId.set(auction.id);
    this.userAuctionService.deleteAuction(auction.id).subscribe({
      next: () => {
        this.auctions.update(prev => prev.filter(a => a.id !== auction.id));
        this.deletingId.set(null);
        this.pendingCount = Math.max(0, this.pendingCount - 1);
      },
      error: (err: Error) => {
        alert('Failed to delete: ' + err.message);
        this.deletingId.set(null);
      }
    });
  }

  onImgError(event: Event): void {
    const img = event.target as HTMLImageElement;
    img.src = 'https://placehold.co/52x52/e2e8f0/94a3b8?text=No+Img';
  }

  getStatusClass(status: string): string {
    const map: Record<string, string> = {
      pending: 'bg-warning bg-opacity-15 text-warning',
      active: 'bg-success bg-opacity-15 text-success',
      closed: 'bg-secondary bg-opacity-15 text-secondary',
      cancelled: 'bg-danger bg-opacity-15 text-danger',
    };
    return map[status] ?? 'bg-secondary bg-opacity-10 text-secondary';
  }

  getStatusIcon(status: string): string {
    const map: Record<string, string> = {
      pending: 'fas fa-clock',
      active: 'fas fa-check-circle',
      closed: 'fas fa-lock',
      cancelled: 'fas fa-times-circle',
    };
    return map[status] ?? 'fas fa-circle';
  }

  getStatusLabel(status: string): string {
    const map: Record<string, string> = {
      pending: 'Pending',
      active: 'Approved',
      closed: 'Closed',
      cancelled: 'Rejected',
    };
    return map[status] ?? status;
  }
}
