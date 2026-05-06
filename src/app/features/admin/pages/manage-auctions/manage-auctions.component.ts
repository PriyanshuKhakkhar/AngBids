import { Component, OnInit, inject, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { AdminService } from '../../services/admin.service';

interface AdminAuction {
  id: number;
  title: string;
  category: string;
  seller: string;
  starting_price: number;
  current_bid: number;
  status: string;
  end_date: string;
  imageUrl?: string;
  user?: { name: string; email: string };
  category_info?: { name: string };
}

@Component({
  selector: 'app-manage-auctions',
  standalone: true,
  imports: [CommonModule, FormsModule],
  template: `
    <!-- Header -->
    <header class="mb-4 pb-2 border-bottom d-flex justify-content-between align-items-end">
        <div>
            <h1 class="h3 fw-bold mb-1 text-dark">Manage Auctions</h1>
            <p class="text-secondary small mb-0">Control and monitor all platform listings</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-bold shadow-sm" (click)="resetFilters()" [disabled]="isLoading()">
                <i class="fas fa-sync-alt me-2" [class.fa-spin]="isLoading()"></i> Reset
            </button>
            <button class="btn btn-primary btn-sm rounded-pill px-4 fw-bold shadow-sm" (click)="onCreateNew()">
                <i class="fas fa-plus me-2"></i> Create New
            </button>
        </div>
    </header>

    <!-- Toast Notification -->
    <div *ngIf="toastMessage()" class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4 border-0 py-3" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ toastMessage() }}
        <button type="button" class="btn-close" (click)="toastMessage.set(null)"></button>
    </div>

    <!-- Filters & Search -->
    <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
        <div class="row g-3 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0 border-secondary border-opacity-10 text-secondary">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" class="form-control border-start-0 border-secondary border-opacity-10 shadow-none ps-0" 
                           placeholder="Search auctions by title or seller..." 
                           [(ngModel)]="searchTerm"
                           (ngModelChange)="onFilterChange()">
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-select border-secondary border-opacity-10 shadow-none fw-semibold" 
                        [(ngModel)]="statusFilter"
                        (change)="onFilterChange()">
                    <option value="All">All Statuses</option>
                    <option value="active">Active</option>
                    <option value="closed">Closed</option>
                    <option value="cancelled">Cancelled</option>
                    <option value="pending">Pending</option>
                </select>
            </div>
            <div class="col-md-4 text-md-end">
                <span class="text-secondary small fw-bold">Showing {{ auctions().length }} items</span>
            </div>
        </div>
    </div>

    <!-- Auctions Table Card -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
        <div *ngIf="isLoading()" class="py-5 text-center">
            <div class="spinner-border text-primary" role="status"></div>
            <div class="text-secondary small mt-2">Fetching auctions...</div>
        </div>

        <div class="table-responsive" *ngIf="!isLoading()">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light bg-opacity-50 text-secondary border-bottom border-secondary border-opacity-10">
                    <tr>
                        <th class="ps-4 py-3 fw-bold small text-uppercase tracking-wider">Title</th>
                        <th class="py-3 fw-bold small text-uppercase tracking-wider">Seller</th>
                        <th class="py-3 fw-bold small text-uppercase tracking-wider text-end">Price Info</th>
                        <th class="py-3 fw-bold small text-uppercase tracking-wider text-center">Status</th>
                        <th class="py-3 fw-bold small text-uppercase tracking-wider">End Date</th>
                        <th class="pe-4 py-3 fw-bold small text-uppercase tracking-wider text-end">Actions</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    <tr *ngFor="let auction of auctions()" [class.table-primary-soft]="selectedAuction?.id === auction.id">
                        <td class="ps-4">
                            <div class="fw-bold text-dark d-block text-truncate" style="max-width: 200px;">{{ auction.title }}</div>
                            <small class="text-muted d-block" style="font-size: 0.75rem;">ID: #{{ auction.id }}</small>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-user-circle me-2 text-secondary opacity-50 fs-5"></i>
                                <span class="small fw-semibold">{{ auction.seller || auction.user?.name || 'Unknown' }}</span>
                            </div>
                        </td>
                        <td class="text-end font-monospace">
                            <div class="small text-muted">Start: ₹{{ auction.starting_price | number:'1.2-2' }}</div>
                            <div class="fw-bold text-success">Cur: ₹{{ (auction.current_bid || auction.starting_price) | number:'1.2-2' }}</div>
                        </td>
                        <td class="text-center">
                            <span class="badge rounded-pill px-3 py-2" 
                                  [ngClass]="getStatusClass(auction.status)">
                                  {{ auction.status | titlecase }}
                            </span>
                        </td>
                        <td>
                            <div class="small fw-semibold">{{ auction.end_date | date:'yyyy-MM-dd' }}</div>
                            <small class="text-muted x-small">at 23:59 PM</small>
                        </td>
                        <td class="pe-4 text-end">
                            <div class="d-flex justify-content-end gap-1">
                                <button class="btn btn-light btn-sm text-primary p-2 transition-all hover-primary" (click)="onView(auction)" title="View details">
                                    <i class="fas fa-info-circle"></i>
                                </button>
                                <button class="btn btn-light btn-sm text-warning p-2 transition-all hover-warning" (click)="onCancel(auction)" *ngIf="auction.status === 'active' || auction.status === 'pending'" title="Cancel auction">
                                    <i class="fas fa-ban"></i>
                                </button>
                                <button class="btn btn-light btn-sm text-danger p-2 transition-all hover-danger" (click)="onDelete(auction.id)" title="Full delete">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr *ngIf="auctions().length === 0">
                        <td colspan="6" class="text-center py-5">
                            <i class="fas fa-search-minus fs-1 text-secondary opacity-25 mb-3"></i>
                            <h6 class="text-secondary">No auctions found matching your criteria.</h6>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Details Section (Shown when viewing) -->
    <div class="card border-0 shadow-lg rounded-4 p-4 bg-white animate-slide-up" *ngIf="selectedAuction">
        <div class="d-flex justify-content-between align-items-start mb-4">
            <h4 class="fw-bold m-0"><i class="fas fa-eye text-primary me-2"></i> Auction Details</h4>
            <button class="btn-close" (click)="selectedAuction = null"></button>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="detail-img rounded-4 bg-light d-flex align-items-center justify-content-center border overflow-hidden" style="height: 200px;">
                    <img *ngIf="selectedAuction.imageUrl" [src]="selectedAuction.imageUrl" class="w-100 h-100 object-fit-cover">
                    <i *ngIf="!selectedAuction.imageUrl" class="fas fa-image fs-1 opacity-25"></i>
                </div>
            </div>
            <div class="col-md-8">
                <h3 class="h4 fw-bold text-dark">{{ selectedAuction.title }}</h3>
                <div class="d-flex gap-3 mb-4">
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">ID: #{{ selectedAuction.id }}</span>
                    <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill">{{ selectedAuction.category || selectedAuction.category_info?.name }}</span>
                </div>
                
                <div class="row g-3">
                    <div class="col-6 col-sm-4">
                        <small class="text-secondary d-block mb-1">Seller</small>
                        <div class="fw-bold">{{ selectedAuction.seller || selectedAuction.user?.name }}</div>
                    </div>
                    <div class="col-6 col-sm-4">
                        <small class="text-secondary d-block mb-1">Starting Price</small>
                        <div class="fw-bold">₹{{ selectedAuction.starting_price | number:'1.2-2' }}</div>
                    </div>
                    <div class="col-6 col-sm-4">
                        <small class="text-secondary d-block mb-1">Current Bid</small>
                        <div class="fw-bold text-success fs-5">₹{{ (selectedAuction.current_bid || selectedAuction.starting_price) | number:'1.2-2' }}</div>
                    </div>
                    <div class="col-6 col-sm-4">
                        <small class="text-secondary d-block mb-1">Status</small>
                        <div class="fw-bold">{{ selectedAuction.status | titlecase }}</div>
                    </div>
                    <div class="col-6 col-sm-4">
                        <small class="text-secondary d-block mb-1">End Date</small>
                        <div class="fw-bold text-danger">{{ selectedAuction.end_date | date:'medium' }}</div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top d-flex gap-2">
                   <button class="btn btn-warning px-4 fw-bold rounded-pill" (click)="onCancel(selectedAuction)" *ngIf="selectedAuction.status === 'active' || selectedAuction.status === 'pending'">
                      <i class="fas fa-ban me-2"></i> Cancel Auction
                   </button>
                   <button class="btn btn-outline-danger px-4 fw-bold rounded-pill" (click)="onDelete(selectedAuction.id)">
                      <i class="fas fa-trash-alt me-2"></i> Delete Forever
                   </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Auction Modal Overlay -->
    <div *ngIf="showCreateModal()" class="modal-backdrop-custom animate-fade-in" (click)="closeCreateModal($event)">
        <div class="modal-card card border-0 shadow-lg rounded-4 p-4 bg-white animate-slide-up" (click)="$event.stopPropagation()">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold m-0 text-primary">
                    <i class="fas fa-plus-circle me-2"></i>Create New Auction
                </h4>
                <button class="btn-close" (click)="showCreateModal.set(false)"></button>
            </div>

            <form #auctionForm="ngForm" (ngSubmit)="onSubmitCreate()">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label small fw-bold text-secondary">Auction Title</label>
                        <input type="text" class="form-control rounded-3 border-secondary border-opacity-10" 
                               placeholder="e.g. Rare Vintage 1960s Chronograph" 
                               [(ngModel)]="newAuction.title" name="title" required #title="ngModel"
                               [class.is-invalid]="title.invalid && title.touched">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-secondary">Select Category</label>
                        <select class="form-select rounded-3 border-secondary border-opacity-10" 
                                [(ngModel)]="newAuction.category_id" name="category_id" required>
                            <option [value]="0" disabled>Select a category</option>
                            <option *ngFor="let cat of categories()" [value]="cat.id">{{ cat.name }}</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-secondary">Assign Seller</label>
                        <select class="form-select rounded-3 border-secondary border-opacity-10" 
                                [(ngModel)]="newAuction.user_id" name="user_id" required>
                            <option [value]="0" disabled>Select a user</option>
                            <option *ngFor="let user of users()" [value]="user.id">{{ user.name }} ({{ user.email }})</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label small fw-bold text-secondary">Description</label>
                        <textarea class="form-control rounded-3 border-secondary border-opacity-10" 
                                  rows="3" placeholder="Provide detailed item information..." 
                                  [(ngModel)]="newAuction.description" name="description" required></textarea>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-secondary">Starting Price (₹)</label>
                        <input type="number" class="form-control rounded-3 border-secondary border-opacity-10" 
                               [(ngModel)]="newAuction.starting_price" name="starting_price" required min="1">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-secondary">Start Time</label>
                        <input type="datetime-local" class="form-control rounded-3 border-secondary border-opacity-10" 
                               [(ngModel)]="newAuction.start_time" name="start_time" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-secondary">End Time</label>
                        <input type="datetime-local" class="form-control rounded-3 border-secondary border-opacity-10" 
                               [(ngModel)]="newAuction.end_time" name="end_time" required>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top d-flex gap-2">
                    <button type="button" class="btn btn-light px-4 rounded-pill fw-bold text-secondary flex-grow-1" (click)="showCreateModal.set(false)">Cancel</button>
                    <button type="submit" class="btn btn-primary px-5 rounded-pill fw-bold flex-grow-1" [disabled]="auctionForm.invalid || isSubmitting()">
                        <span *ngIf="!isSubmitting()"><i class="fas fa-check me-2"></i>Create Auction</span>
                        <span *ngIf="isSubmitting()"><i class="fas fa-circle-notch fa-spin me-2"></i>Processing...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
  `,
  styles: [`
    .animate-slide-up { animation: slideUp 0.3s ease-out; }
    .animate-fade-in { animation: fadeIn 0.2s ease-out; }
    @keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    
    .modal-backdrop-custom {
        position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
        background: rgba(0,0,0,0.5); backdrop-filter: blur(4px);
        z-index: 1050; display: flex; align-items: center; justify-content: center;
    }
    .modal-card { width: 100%; max-width: 700px; max-height: 90vh; overflow-y: auto; }
    
    .table-hover tbody tr:hover { background-color: rgba(78, 115, 233, 0.02); }
    .table-primary-soft { background-color: rgba(78, 115, 233, 0.05) !important; }
    .hover-primary:hover { background-color: rgba(13, 110, 253, 0.1) !important; scale: 1.1; }
    .hover-warning:hover { background-color: rgba(255, 193, 7, 0.1) !important; scale: 1.1; }
    .hover-danger:hover { background-color: rgba(220, 53, 69, 0.1) !important; scale: 1.1; }
    .transition-all { transition: all 0.2s ease-in-out; }
    .x-small { font-size: 0.7rem; }
    .object-fit-cover { object-fit: cover; }
  `]
})
export class ManageAuctionsComponent implements OnInit {
  private adminService = inject(AdminService);
  
  searchTerm: string = '';
  statusFilter: string = 'All';
  selectedAuction: AdminAuction | null = null;
  auctions = signal<AdminAuction[]>([]);
  isLoading = signal(false);
  toastMessage = signal<string | null>(null);

  // Creation State
  showCreateModal = signal(false);
  isSubmitting = signal(false);
  categories = signal<any[]>([]);
  users = signal<any[]>([]);
  newAuction = {
    title: '',
    category_id: 0,
    user_id: 0,
    description: '',
    starting_price: 0,
    start_time: '',
    end_time: '',
    status: 'pending'
  };

  private searchTimeout: any;

  ngOnInit() {
    this.loadAuctions();
    this.loadInitialData();
  }

  loadAuctions() {
    this.isLoading.set(true);
    const params: any = {};
    if (this.searchTerm) params.search = this.searchTerm;
    if (this.statusFilter !== 'All') params.status = this.statusFilter;

    this.adminService.getAuctions(params).subscribe({
      next: (res: any) => {
        this.auctions.set(res.data || res);
        this.isLoading.set(false);
      },
      error: (err) => {
        console.error('Failed to load auctions', err);
        this.isLoading.set(false);
      }
    });
  }

  loadInitialData() {
    this.adminService.getCategories().subscribe(res => this.categories.set(res));
    this.adminService.getUsers().subscribe(res => this.users.set(res.data || res));
  }

  onFilterChange() {
    clearTimeout(this.searchTimeout);
    this.searchTimeout = setTimeout(() => {
      this.loadAuctions();
    }, 400);
  }

  onView(auction: AdminAuction) {
    this.selectedAuction = auction;
  }

  onCreateNew() {
    this.resetNewAuctionForm();
    this.showCreateModal.set(true);
  }

  closeCreateModal(event: Event) {
    this.showCreateModal.set(false);
  }

  onSubmitCreate() {
    if (this.isSubmitting()) return;

    this.isSubmitting.set(true);
    this.adminService.createAuction(this.newAuction).subscribe({
      next: (res) => {
        this.showToast('New auction created successfully!');
        this.showCreateModal.set(false);
        this.isSubmitting.set(false);
        this.loadAuctions();
      },
      error: (err) => {
        console.error('Failed to create auction', err);
        alert('Failed to create auction: ' + (err.error?.message || 'Unknown error'));
        this.isSubmitting.set(false);
      }
    });
  }

  resetNewAuctionForm() {
    this.newAuction = {
      title: '',
      category_id: 0,
      user_id: 0,
      description: '',
      starting_price: 0,
      start_time: this.formatDate(new Date()),
      end_time: this.formatDate(new Date(Date.now() + 7 * 24 * 60 * 60 * 1000)), // +7 days
      status: 'pending'
    };
  }

  formatDate(date: Date): string {
    return date.toISOString().slice(0, 16);
  }

  onCancel(auction: AdminAuction) {
    if (confirm(`Are you sure you want to cancel the auction "${auction.title}"?`)) {
        this.adminService.rejectAuction(auction.id, 'Cancelled by Admin').subscribe({
          next: () => {
            this.showToast('Auction cancelled successfully');
            this.loadAuctions();
            if (this.selectedAuction?.id === auction.id) this.selectedAuction = null;
          }
        });
    }
  }

  onDelete(id: number) {
    if (confirm('Are you sure you want to permanently delete this auction? This action cannot be undone.')) {
        this.adminService.deleteAuction(id).subscribe({
          next: () => {
            this.showToast('Auction deleted successfully');
            this.loadAuctions();
            if (this.selectedAuction?.id === id) this.selectedAuction = null;
          }
        });
    }
  }

  resetFilters() {
    this.searchTerm = '';
    this.statusFilter = 'All';
    this.loadAuctions();
    this.showToast('Filters reset and data refreshed');
  }

  getStatusClass(status: string): string {
    const s = status?.toLowerCase();
    if (s === 'active') return 'bg-success bg-opacity-10 text-success';
    if (s === 'closed') return 'bg-secondary bg-opacity-10 text-secondary';
    if (s === 'cancelled' || s === 'rejected') return 'bg-danger bg-opacity-10 text-danger';
    if (s === 'pending') return 'bg-warning bg-opacity-10 text-warning';
    return 'bg-dark bg-opacity-10 text-dark';
  }

  showToast(msg: string) {
    this.toastMessage.set(msg);
    setTimeout(() => this.toastMessage.set(null), 3000);
  }
}
