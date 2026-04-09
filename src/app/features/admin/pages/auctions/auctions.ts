import { Component, OnInit, inject, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { AdminService } from '../../services/admin.service';

@Component({
  selector: 'app-admin-auctions',
  standalone: true,
  imports: [CommonModule],
  template: `
    <div class="glass-panel p-4 shadow-sm border-0 h-100">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h5 mb-0 fw-bold text-dark"><i class="fas fa-gavel text-gold me-2"></i>Auction Control</h2>
        <div class="d-flex gap-2">
            <select class="form-select border-0 bg-light small fw-bold text-dark rounded-3 shadow-none" style="width: 150px;">
                <option value="all">All Status</option>
                <option value="pending">Pending Review</option>
                <option value="active">Active Now</option>
            </select>
            <button class="btn btn-gold btn-sm px-4 fw-bold rounded-pill shadow-sm">Audit Log <i class="fas fa-list ms-1"></i></button>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead>
            <tr class="text-secondary small text-uppercase letter-spacing-1">
               <th class="border-0">Lot ID</th>
               <th class="border-0">Item Details</th>
               <th class="border-0">Valuation</th>
               <th class="border-0">Traffic</th>
               <th class="border-0">Status</th>
               <th class="border-0 text-end">Control</th>
            </tr>
          </thead>
          <tbody>
            <tr *ngFor="let auction of auctions()" class="align-middle">
                <td class="text-secondary small py-3">#{{ auction.id }}</td>
                <td class="py-3">
                  <div class="fw-bold small text-dark">{{ auction.title }}</div>
                  <div class="text-secondary x-small"><i class="fas fa-user-circle me-1"></i>{{ auction.seller }}</div>
                </td>
                <td class="py-3">
                  <div class="fw-bold text-primary small">₹{{ auction.price }}</div>
                </td>
                <td class="py-3">
                  <span class="badge bg-light text-dark px-2 py-1 x-small border fw-bold">{{ auction.bids }} bids active</span>
                </td>
                <td class="py-3">
                  <span class="badge" [class]="auction.status === 'active' ? 'bg-success-soft text-success' : 'bg-warning-soft text-warning'" style="font-size: 0.65rem;">
                    {{ (auction.status === 'pending' ? 'Pending Review' : auction.status) | uppercase }}
                  </span>
                </td>
                <td class="text-end py-3">
                  <button class="btn btn-outline-primary btn-sm border-0 me-1" title="View Details"><i class="fas fa-eye"></i></button>
                  <button *ngIf="auction.status === 'pending'" (click)="onApprove(auction.id)" class="btn btn-outline-success btn-sm border-0 me-1" title="Approve Auction"><i class="fas fa-check-circle"></i></button>
                  <button (click)="onBan(auction.id)" class="btn btn-outline-danger btn-sm border-0" title="Revoke/Ban"><i class="fas fa-ban"></i></button>
                </td>
            </tr>
            <tr *ngIf="auctions().length === 0">
              <td colspan="6" class="text-center py-5 text-secondary small">No auction data found or loading cache...</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  `
})
export class AdminAuctions implements OnInit {
  private adminService = inject(AdminService);

  auctions = signal<any[]>([
    { id: 402, title: 'Luxury Villa #12', seller: 'Estate Group', price: '12,40,000', bids: 14, status: 'active' },
    { id: 403, title: 'Vintage Watch #44', seller: 'Anil Kumar', price: '4,500', bids: 4, status: 'pending' },
    { id: 404, title: 'Classic Car #2', seller: 'Auto Masters', price: '8,20,000', bids: 0, status: 'pending' }
  ]);

  ngOnInit() {
    this.loadAuctions();
  }

  loadAuctions() {
    this.adminService.getAuctions().subscribe({
      next: (data) => {
        if (data && Array.isArray(data)) this.auctions.set(data);
      },
      error: (err) => console.warn('Using dummy auctions data.')
    });
  }

  onApprove(id: number) {
    this.adminService.approveAuction(id).subscribe({
      next: () => {
        this.auctions.update(list => list.map(a => a.id === id ? { ...a, status: 'active' } : a));
      },
      error: (err) => {
        // Fallback for demo
        this.auctions.update(list => list.map(a => a.id === id ? { ...a, status: 'active' } : a));
      }
    });
  }

  onBan(id: number) {
    if (confirm('Are you sure you want to revoke this auction listing?')) {
      this.adminService.deleteAuction(id).subscribe({
        next: () => {
          this.auctions.update(list => list.filter(a => a.id !== id));
        },
        error: (err) => {
          // Fallback for demo
          this.auctions.update(list => list.filter(a => a.id !== id));
        }
      });
    }
  }
}
