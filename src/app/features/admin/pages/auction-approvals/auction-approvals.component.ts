import { Component, OnInit, inject, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { AdminAuctionService, AdminAuction } from '../../../../core/services/admin-auction.service';

@Component({
  selector: 'app-auction-approvals',
  standalone: true,
  imports: [CommonModule, FormsModule],
  template: `
    <!-- Page Header -->
    <header class="mb-4 pb-2 border-bottom d-flex justify-content-between align-items-end">
      <div>
        <h1 class="h3 fw-bold mb-1 text-dark d-flex align-items-center gap-2">
          <i class="fas fa-gavel text-primary"></i>
          Auction Approvals
        </h1>
        <p class="text-secondary small mb-0">
          Review and approve or reject auction submissions from users.
        </p>
      </div>
      <div class="d-flex gap-2 align-items-center">
        <span *ngIf="pendingCount() > 0"
              class="badge bg-warning bg-opacity-15 text-warning rounded-pill px-3 py-2 fs-6 fw-bold">
          {{ pendingCount() }} pending
        </span>
        <button class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-bold"
                (click)="loadAuctions()" [disabled]="isLoading()">
          <i class="fas fa-sync-alt me-1" [class.fa-spin]="isLoading()"></i> Refresh
        </button>
      </div>
    </header>

    <!-- Filters -->
    <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
      <div class="row g-2 align-items-center">
        <div class="col-md-5">
          <div class="input-group">
            <span class="input-group-text bg-transparent border-end-0 text-secondary">
              <i class="fas fa-search"></i>
            </span>
            <input type="text"
                   class="form-control border-start-0 shadow-none"
                   placeholder="Search by title or seller name..."
                   [(ngModel)]="searchTerm"
                   (ngModelChange)="onSearch()">
          </div>
        </div>
        <div class="col-md-3">
          <select class="form-select shadow-none fw-semibold" [(ngModel)]="statusFilter" (ngModelChange)="onStatusChange()">
            <option value="all">All Statuses</option>
            <option value="pending">Pending Review</option>
            <option value="active">Approved (Active)</option>
            <option value="closed">Closed</option>
            <option value="cancelled">Rejected</option>
          </select>
        </div>
        <div class="col-md-4 text-md-end">
          <span class="text-secondary small fw-semibold">
            {{ totalCount() }} auction{{ totalCount() !== 1 ? 's' : '' }} found
          </span>
        </div>
      </div>
    </div>

    <!-- Toast Notification -->
    <div *ngIf="toastMessage()"
         class="alert alert-dismissible rounded-3 border-0 shadow-sm mb-4 d-flex align-items-center gap-2"
         [class.alert-success]="toastType() === 'success'"
         [class.alert-danger]="toastType() === 'error'">
      <i [class]="toastType() === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle'"></i>
      <span class="small fw-semibold">{{ toastMessage() }}</span>
      <button type="button" class="btn-close ms-auto" (click)="toastMessage.set(null)"></button>
    </div>

    <!-- Loading State -->
    <div *ngIf="isLoading()" class="text-center py-5">
      <div class="spinner-border text-primary" role="status"></div>
      <p class="text-secondary mt-3 small">Loading auctions...</p>
    </div>

    <!-- Table -->
    <div *ngIf="!isLoading()" class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="bg-light border-bottom">
            <tr>
              <th class="ps-4 py-3 fw-bold small text-uppercase text-secondary">Auction</th>
              <th class="py-3 fw-bold small text-uppercase text-secondary">Seller</th>
              <th class="py-3 fw-bold small text-uppercase text-secondary">Category</th>
              <th class="py-3 fw-bold small text-uppercase text-secondary text-end">Starting Price</th>
              <th class="py-3 fw-bold small text-uppercase text-secondary">Timeline</th>
              <th class="py-3 fw-bold small text-uppercase text-secondary text-center">Status</th>
              <th class="pe-4 py-3 fw-bold small text-uppercase text-secondary text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr *ngFor="let auction of auctions()" class="border-bottom border-light">
              <!-- Title + Image -->
              <td class="ps-4">
                <div class="d-flex align-items-center gap-3">
                  <img [src]="auction.imageUrl"
                       class="rounded-3 flex-shrink-0 border"
                       width="52" height="52"
                       style="object-fit: cover;"
                       [alt]="auction.title"
                       (error)="onImgError($event)">
                  <div>
                    <div class="fw-semibold text-dark text-truncate" style="max-width: 200px;" [title]="auction.title">
                      {{ auction.title }}
                    </div>
                    <small class="text-muted">ID #{{ auction.id }}</small>
                  </div>
                </div>
              </td>

              <!-- Seller -->
              <td>
                <div class="fw-semibold small">{{ auction.sellerName }}</div>
                <small class="text-muted">{{ auction.sellerEmail || '—' }}</small>
              </td>

              <!-- Category -->
              <td>
                <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 small">
                  {{ auction.category }}
                </span>
              </td>

              <!-- Starting Price -->
              <td class="text-end font-monospace fw-semibold">
                ₹{{ auction.startingPrice | number:'1.2-2' }}
              </td>

              <!-- Timeline -->
              <td>
                <div class="small">
                  <div class="text-muted">Start: <span class="text-dark fw-semibold">{{ auction.startTime | date:'dd MMM yy, h:mm a' }}</span></div>
                  <div class="text-muted">End: <span class="text-dark fw-semibold">{{ auction.endTime | date:'dd MMM yy, h:mm a' }}</span></div>
                </div>
              </td>

              <!-- Status Badge -->
              <td class="text-center">
                <span class="badge rounded-pill px-3 py-2"
                      [ngClass]="getStatusClass(auction.status)">
                  {{ getStatusLabel(auction.status) }}
                </span>
              </td>

              <!-- Actions -->
              <td class="pe-4 text-end">
                <div class="d-flex gap-1 justify-content-end">
                  <!-- Preview details -->
                  <button class="btn btn-light btn-sm text-primary p-2 action-btn"
                          title="View details"
                          (click)="openDetails(auction)">
                    <i class="fas fa-eye"></i>
                  </button>

                  <!-- Approve -->
                  <button *ngIf="auction.status === 'pending'"
                          class="btn btn-success btn-sm px-3 py-2 fw-bold shadow-sm rounded-pill"
                          title="Approve this auction"
                          [disabled]="processingId() === auction.id"
                          (click)="onApprove(auction)">
                    <span *ngIf="processingId() !== auction.id">
                      <i class="fas fa-check me-1"></i>Approve
                    </span>
                    <span *ngIf="processingId() === auction.id">
                      <span class="spinner-border spinner-border-sm me-1"></span>...
                    </span>
                  </button>

                  <!-- Reject -->
                  <button *ngIf="auction.status === 'pending'"
                          class="btn btn-outline-danger btn-sm px-3 py-2 fw-bold rounded-pill"
                          title="Reject this auction"
                          [disabled]="processingId() === auction.id"
                          (click)="promptReject(auction)">
                    <i class="fas fa-times me-1"></i>Reject
                  </button>
                </div>
              </td>
            </tr>

            <!-- Empty Row -->
            <tr *ngIf="auctions().length === 0">
              <td colspan="7" class="text-center py-5">
                <i class="fas fa-check-double fs-1 text-success opacity-25 mb-3 d-block"></i>
                <h6 class="text-secondary">No auctions found for this filter.</h6>
                <p class="text-muted small">
                  {{ statusFilter === 'pending' ? 'No pending submissions — you are all caught up!' : 'Try changing the filter.' }}
                </p>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div *ngIf="totalPages() > 1"
           class="d-flex justify-content-between align-items-center px-4 py-3 border-top">
        <small class="text-secondary">Page {{ currentPage() }} of {{ totalPages() }}</small>
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

    <!-- Detail Panel (slide-in card) -->
    <div *ngIf="selectedAuction()"
         class="card border-0 shadow-lg rounded-4 p-4 bg-white detail-card">
      <div class="d-flex justify-content-between align-items-start mb-4">
        <h4 class="fw-bold m-0">
          <i class="fas fa-info-circle text-primary me-2"></i>Auction Details
        </h4>
        <button class="btn-close" (click)="selectedAuction.set(null)"></button>
      </div>

      <div class="row g-4">
        <div class="col-md-4">
          <img [src]="selectedAuction()!.imageUrl"
               class="w-100 rounded-4 border"
               style="height: 200px; object-fit: cover;"
               [alt]="selectedAuction()!.title"
               (error)="onImgError($event)">
        </div>
        <div class="col-md-8">
          <h3 class="h5 fw-bold text-dark mb-3">{{ selectedAuction()!.title }}</h3>
          <p class="text-secondary small mb-4">{{ selectedAuction()!.description }}</p>
          <div class="row g-3 text-sm">
            <div class="col-6 col-sm-4">
              <small class="text-secondary d-block mb-1">Seller</small>
              <div class="fw-semibold">{{ selectedAuction()!.sellerName }}</div>
            </div>
            <div class="col-6 col-sm-4">
              <small class="text-secondary d-block mb-1">Category</small>
              <div class="fw-semibold">{{ selectedAuction()!.category }}</div>
            </div>
            <div class="col-6 col-sm-4">
              <small class="text-secondary d-block mb-1">Starting Price</small>
              <div class="fw-semibold text-success">₹{{ selectedAuction()!.startingPrice | number:'1.2-2' }}</div>
            </div>
            <div class="col-6 col-sm-4">
              <small class="text-secondary d-block mb-1">Status</small>
              <span class="badge rounded-pill px-3 py-2" [ngClass]="getStatusClass(selectedAuction()!.status)">
                {{ getStatusLabel(selectedAuction()!.status) }}
              </span>
            </div>
            <div class="col-6 col-sm-4">
              <small class="text-secondary d-block mb-1">Start Time</small>
              <div class="fw-semibold small">{{ selectedAuction()!.startTime | date:'dd MMM yyyy, h:mm a' }}</div>
            </div>
            <div class="col-6 col-sm-4">
              <small class="text-secondary d-block mb-1">End Time</small>
              <div class="fw-semibold small">{{ selectedAuction()!.endTime | date:'dd MMM yyyy, h:mm a' }}</div>
            </div>
            <div class="col-12">
              <small class="text-secondary d-block mb-1">Submitted</small>
              <div class="fw-semibold small">{{ selectedAuction()!.submittedAt | date:'dd MMM yyyy, h:mm a' }}</div>
            </div>
          </div>

          <!-- Action Buttons in Detail Panel -->
          <div *ngIf="selectedAuction()!.status === 'pending'"
               class="mt-4 pt-3 border-top d-flex gap-2">
            <button class="btn btn-success px-4 fw-bold rounded-pill"
                    [disabled]="processingId() === selectedAuction()!.id"
                    (click)="onApprove(selectedAuction()!)">
              <i class="fas fa-check me-2"></i>Approve
            </button>
            <button class="btn btn-outline-danger px-4 fw-bold rounded-pill"
                    [disabled]="processingId() === selectedAuction()!.id"
                    (click)="promptReject(selectedAuction()!)">
              <i class="fas fa-times me-2"></i>Reject
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Reject Reason Modal (inline) -->
    <div *ngIf="showRejectModal()" class="modal-backdrop-custom">
      <div class="reject-modal card border-0 shadow-lg rounded-4 p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="fw-bold mb-0 text-danger">
            <i class="fas fa-times-circle me-2"></i>Reject Auction
          </h5>
          <button class="btn-close" (click)="showRejectModal.set(false)"></button>
        </div>
        <p class="text-secondary small mb-3">
          You are about to reject: <strong>{{ rejectTarget()?.title }}</strong><br>
          Please provide a reason that will be sent to the seller.
        </p>
        <textarea class="form-control mb-3"
                  rows="4"
                  [(ngModel)]="rejectReason"
                  placeholder="e.g. The item description does not match the category. Please resubmit with more details."></textarea>
        <div class="d-flex gap-2 justify-content-end">
          <button class="btn btn-outline-secondary rounded-pill px-4"
                  (click)="showRejectModal.set(false)">
            Cancel
          </button>
          <button class="btn btn-danger rounded-pill px-4 fw-bold"
                  [disabled]="!rejectReason.trim()"
                  (click)="confirmReject()">
            <i class="fas fa-times me-2"></i>Confirm Reject
          </button>
        </div>
      </div>
    </div>
  `,
  styles: [`
    .action-btn:hover { transform: scale(1.1); transition: transform 0.15s; }
    .detail-card { animation: slideUp 0.25s ease-out; }
    @keyframes slideUp {
      from { transform: translateY(16px); opacity: 0; }
      to   { transform: translateY(0); opacity: 1; }
    }
    .modal-backdrop-custom {
      position: fixed; inset: 0;
      background: rgba(0,0,0,0.45);
      backdrop-filter: blur(2px);
      z-index: 1050;
      display: flex; align-items: center; justify-content: center;
    }
    .reject-modal {
      width: 100%; max-width: 520px; margin: 1rem;
    }
  `]
})
export class AuctionApprovalsComponent implements OnInit {
  private adminAuctionService = inject(AdminAuctionService);

  auctions = signal<AdminAuction[]>([]);
  isLoading = signal(false);
  processingId = signal<number | null>(null);
  selectedAuction = signal<AdminAuction | null>(null);
  toastMessage = signal<string | null>(null);
  toastType = signal<'success' | 'error'>('success');
  showRejectModal = signal(false);
  rejectTarget = signal<AdminAuction | null>(null);
  rejectReason = '';
  pendingCount = signal(0);
  totalCount = signal(0);
  currentPage = signal(1);
  totalPages = signal(1);

  searchTerm = '';
  statusFilter = 'pending'; // default to pending for admin's daily workflow
  private searchTimeout: any;

  ngOnInit(): void {
    this.loadAuctions();
  }

  loadAuctions(): void {
    this.isLoading.set(true);

    this.adminAuctionService.getAuctions({
      status: this.statusFilter,
      search: this.searchTerm || undefined,
      page: this.currentPage()
    }).subscribe({
      next: (res) => {
        this.auctions.set(res.data);
        this.totalCount.set(res.total);
        this.totalPages.set(res.last_page);
        this.isLoading.set(false);

        // Update pending count badge
        if (this.statusFilter === 'pending') {
          this.pendingCount.set(res.total);
        } else {
          this.loadPendingCount();
        }
      },
      error: (err: Error) => {
        this.showToast(err.message, 'error');
        this.isLoading.set(false);
      }
    });
  }

  loadPendingCount(): void {
    this.adminAuctionService.getAuctions({ status: 'pending' }).subscribe({
      next: (res) => this.pendingCount.set(res.total),
      error: () => {}
    });
  }

  onSearch(): void {
    clearTimeout(this.searchTimeout);
    this.searchTimeout = setTimeout(() => {
      this.currentPage.set(1);
      this.loadAuctions();
    }, 400);
  }

  onStatusChange(): void {
    this.currentPage.set(1);
    this.loadAuctions();
  }

  changePage(page: number): void {
    this.currentPage.set(page);
    this.loadAuctions();
  }

  openDetails(auction: AdminAuction): void {
    this.selectedAuction.set(auction);
  }

  onApprove(auction: AdminAuction): void {
    if (!confirm(`Approve "${auction.title}" and make it publicly visible?`)) return;

    this.processingId.set(auction.id);
    this.adminAuctionService.approveAuction(auction.id).subscribe({
      next: (res) => {
        this.processingId.set(null);
        // Update status locally for immediate feedback
        this.auctions.update(prev =>
          prev.map(a => a.id === auction.id ? { ...a, status: 'active' as const } : a)
        );
        if (this.selectedAuction()?.id === auction.id) {
          this.selectedAuction.update(a => a ? { ...a, status: 'active' } : null);
        }
        this.pendingCount.update(c => Math.max(0, c - 1));
        this.showToast(res.message || 'Auction approved and is now live!', 'success');
      },
      error: (err: Error) => {
        this.processingId.set(null);
        this.showToast(err.message, 'error');
      }
    });
  }

  promptReject(auction: AdminAuction): void {
    this.rejectTarget.set(auction);
    this.rejectReason = '';
    this.showRejectModal.set(true);
  }

  confirmReject(): void {
    const target = this.rejectTarget();
    if (!target || !this.rejectReason.trim()) return;

    this.showRejectModal.set(false);
    this.processingId.set(target.id);

    this.adminAuctionService.rejectAuction(target.id, this.rejectReason.trim()).subscribe({
      next: (res) => {
        this.processingId.set(null);
        this.auctions.update(prev =>
          prev.map(a => a.id === target.id ? { ...a, status: 'cancelled' as const } : a)
        );
        if (this.selectedAuction()?.id === target.id) {
          this.selectedAuction.update(a => a ? { ...a, status: 'cancelled' } : null);
        }
        this.pendingCount.update(c => Math.max(0, c - 1));
        this.showToast(res.message || 'Auction rejected and seller notified.', 'success');
      },
      error: (err: Error) => {
        this.processingId.set(null);
        this.showToast(err.message, 'error');
      }
    });
  }

  onImgError(event: Event): void {
    const img = event.target as HTMLImageElement;
    img.src = 'https://placehold.co/52x52/1a1a2e/94a3b8?text=N/A';
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

  getStatusLabel(status: string): string {
    const map: Record<string, string> = {
      pending: 'Pending Review',
      active: 'Approved',
      closed: 'Closed',
      cancelled: 'Rejected',
    };
    return map[status] ?? status;
  }

  private showToast(message: string, type: 'success' | 'error'): void {
    this.toastMessage.set(message);
    this.toastType.set(type);
    setTimeout(() => this.toastMessage.set(null), 5000);
  }
}
