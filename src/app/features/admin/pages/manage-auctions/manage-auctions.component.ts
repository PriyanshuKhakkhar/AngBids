import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

interface MockAuction {
  id: number;
  title: string;
  category: string;
  seller: string;
  starting_price: number;
  current_bid: number;
  status: 'Active' | 'Closed' | 'Cancelled' | 'Pending';
  end_date: string;
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
            <button class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-bold shadow-sm" (click)="resetFilters()">
                <i class="fas fa-sync-alt me-2"></i> Reset
            </button>
            <button class="btn btn-primary btn-sm rounded-pill px-4 fw-bold shadow-sm">
                <i class="fas fa-plus me-2"></i> Create New
            </button>
        </div>
    </header>

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
                           [(ngModel)]="searchTerm">
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-select border-secondary border-opacity-10 shadow-none fw-semibold" [(ngModel)]="statusFilter">
                    <option value="All">All Statuses</option>
                    <option value="Active">Active</option>
                    <option value="Closed">Closed</option>
                    <option value="Cancelled">Cancelled</option>
                    <option value="Pending">Pending</option>
                </select>
            </div>
            <div class="col-md-4 text-md-end">
                <span class="text-secondary small fw-bold">Showing {{ filteredAuctions.length }} of {{ auctions.length }} items</span>
            </div>
        </div>
    </div>

    <!-- Auctions Table Card -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
        <div class="table-responsive">
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
                    <tr *ngFor="let auction of filteredAuctions" [class.table-primary-soft]="selectedAuction?.id === auction.id">
                        <td class="ps-4">
                            <div class="fw-bold text-dark d-block text-truncate" style="max-width: 200px;">{{ auction.title }}</div>
                            <small class="text-muted d-block" style="font-size: 0.75rem;">ID: #{{ auction.id }}</small>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-user-circle me-2 text-secondary opacity-50 fs-5"></i>
                                <span class="small fw-semibold">{{ auction.seller }}</span>
                            </div>
                        </td>
                        <td class="text-end font-monospace">
                            <div class="small text-muted">Start: {{ auction.starting_price | currency }}</div>
                            <div class="fw-bold text-success">Cur: {{ auction.current_bid | currency }}</div>
                        </td>
                        <td class="text-center">
                            <span class="badge rounded-pill px-3 py-2" 
                                  [ngClass]="{
                                    'bg-success bg-opacity-10 text-success': auction.status === 'Active',
                                    'bg-secondary bg-opacity-10 text-secondary': auction.status === 'Closed',
                                    'bg-danger bg-opacity-10 text-danger': auction.status === 'Cancelled',
                                    'bg-warning bg-opacity-10 text-warning': auction.status === 'Pending'
                                  }">
                                  {{ auction.status }}
                            </span>
                        </td>
                        <td>
                            <div class="small fw-semibold">{{ auction.end_date }}</div>
                            <small class="text-muted x-small">at 23:59 PM</small>
                        </td>
                        <td class="pe-4 text-end">
                            <div class="d-flex justify-content-end gap-1">
                                <button class="btn btn-light btn-sm text-primary p-2 transition-all hover-primary" (click)="onView(auction)" title="View details">
                                    <i class="fas fa-info-circle"></i>
                                </button>
                                <button class="btn btn-light btn-sm text-warning p-2 transition-all hover-warning" (click)="onCancel(auction)" *ngIf="auction.status === 'Active' || auction.status === 'Pending'" title="Cancel auction">
                                    <i class="fas fa-ban"></i>
                                </button>
                                <button class="btn btn-light btn-sm text-danger p-2 transition-all hover-danger" (click)="onDelete(auction.id)" title="Full delete">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr *ngIf="filteredAuctions.length === 0">
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
                <div class="detail-img rounded-4 bg-light d-flex align-items-center justify-content-center border" style="height: 200px;">
                    <i class="fas fa-image fs-1 opacity-25"></i>
                </div>
            </div>
            <div class="col-md-8">
                <h3 class="h4 fw-bold text-dark">{{ selectedAuction.title }}</h3>
                <div class="d-flex gap-3 mb-4">
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">ID: #{{ selectedAuction.id }}</span>
                    <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill">{{ selectedAuction.category }}</span>
                </div>
                
                <div class="row g-3">
                    <div class="col-6 col-sm-4">
                        <small class="text-secondary d-block mb-1">Seller</small>
                        <div class="fw-bold">{{ selectedAuction.seller }}</div>
                    </div>
                    <div class="col-6 col-sm-4">
                        <small class="text-secondary d-block mb-1">Starting Price</small>
                        <div class="fw-bold">{{ selectedAuction.starting_price | currency }}</div>
                    </div>
                    <div class="col-6 col-sm-4">
                        <small class="text-secondary d-block mb-1">Current Bid</small>
                        <div class="fw-bold text-success fs-5">{{ selectedAuction.current_bid | currency }}</div>
                    </div>
                    <div class="col-6 col-sm-4">
                        <small class="text-secondary d-block mb-1">Status</small>
                        <div class="fw-bold">{{ selectedAuction.status }}</div>
                    </div>
                    <div class="col-6 col-sm-4">
                        <small class="text-secondary d-block mb-1">End Date</small>
                        <div class="fw-bold text-danger">{{ selectedAuction.end_date }}</div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top d-flex gap-2">
                   <button class="btn btn-warning px-4 fw-bold" (click)="onCancel(selectedAuction)" *ngIf="selectedAuction.status === 'Active' || selectedAuction.status === 'Pending'">
                      <i class="fas fa-ban me-2"></i> Cancel Auction
                   </button>
                   <button class="btn btn-outline-danger px-4 fw-bold" (click)="onDelete(selectedAuction.id)">
                      <i class="fas fa-trash-alt me-2"></i> Delete Forever
                   </button>
                </div>
            </div>
        </div>
    </div>
  `,
  styles: [`
    .animate-slide-up {
        animation: slideUp 0.3s ease-out;
    }
    @keyframes slideUp {
        from { transform: translateY(20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    .table-hover tbody tr:hover { background-color: rgba(78, 115, 233, 0.02); }
    .table-primary-soft { background-color: rgba(78, 115, 233, 0.05) !important; }
    .hover-primary:hover { background-color: rgba(13, 110, 253, 0.1) !important; scale: 1.1; }
    .hover-warning:hover { background-color: rgba(255, 193, 7, 0.1) !important; scale: 1.1; }
    .hover-danger:hover { background-color: rgba(220, 53, 69, 0.1) !important; scale: 1.1; }
    .transition-all { transition: all 0.2s ease-in-out; }
    .x-small { font-size: 0.7rem; }
  `]
})
export class ManageAuctionsComponent {
  searchTerm: string = '';
  statusFilter: string = 'All';
  selectedAuction: MockAuction | null = null;

  auctions: MockAuction[] = [
    { id: 2601, title: 'MacBook Pro M2 14" (Space Gray)', category: 'Electronics', seller: 'John Doe', starting_price: 1000, current_bid: 1250, status: 'Active', end_date: '2026-04-15' },
    { id: 2602, title: 'Vintage Rolex Datejust 36mm', category: 'Collectibles', seller: 'Jane Smith', starting_price: 200, current_bid: 450, status: 'Active', end_date: '2026-04-12' },
    { id: 2603, title: 'Nike Air Max 90 "Bacon" (DS)', category: 'Fashion', seller: 'Bob Johnson', starting_price: 50, current_bid: 80, status: 'Closed', end_date: '2026-04-08' },
    { id: 2604, title: 'L-Shaped Modern Leather Couch', category: 'Home Decor', seller: 'Alice Brown', starting_price: 300, current_bid: 300, status: 'Cancelled', end_date: '2026-04-10' },
    { id: 2605, title: 'Tesla Model 3 Performance 2023', category: 'Vehicles', seller: 'Mike Wilson', starting_price: 5000, current_bid: 25000, status: 'Pending', end_date: '2026-04-20' },
    { id: 2606, title: 'Diamond Solitaire Pendant 1ct', category: 'Jewelry', seller: 'Sarah Connor', starting_price: 800, current_bid: 1100, status: 'Active', end_date: '2026-04-18' }
  ];

  get filteredAuctions() {
    return this.auctions.filter(a => {
      const ts = this.searchTerm.toLowerCase();
      const matchSearch = a.title.toLowerCase().includes(ts) || a.seller.toLowerCase().includes(ts);
      const matchStatus = this.statusFilter === 'All' || a.status === this.statusFilter;
      return matchSearch && matchStatus;
    });
  }

  onView(auction: MockAuction) {
    this.selectedAuction = auction;
  }

  onCancel(auction: MockAuction) {
    if (confirm(`Are you sure you want to cancel the auction "${auction.title}"?`)) {
        auction.status = 'Cancelled';
        if (this.selectedAuction?.id === auction.id) {
            this.selectedAuction.status = 'Cancelled';
        }
    }
  }

  onDelete(id: number) {
    if (confirm('Are you sure you want to permanently delete this auction? This action cannot be undone.')) {
        this.auctions = this.auctions.filter(a => a.id !== id);
        if (this.selectedAuction?.id === id) {
            this.selectedAuction = null;
        }
    }
  }

  resetFilters() {
    this.searchTerm = '';
    this.statusFilter = 'All';
  }
}
