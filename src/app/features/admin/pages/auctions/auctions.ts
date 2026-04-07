import { Component, signal } from '@angular/core';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-admin-auctions',
  standalone: true,
  imports: [CommonModule],
  template: `
    <div class="glass-panel p-4 shadow-sm border-0 h-100">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h5 mb-0 fw-bold">Auction Control</h2>
        <div class="d-flex gap-2">
            <select class="form-select form-select-elite py-2 px-3 small" style="width: 150px;">
                <option value="all">All Status</option>
                <option value="pending">Pending</option>
                <option value="active">Active</option>
            </select>
            <button class="btn btn-gold btn-sm px-4">Download PDF</button>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-dark table-hover mb-0">
          <thead>
            <tr class="text-secondary small text-uppercase">
               <th>ID</th>
               <th>Title / Seller</th>
               <th>Current Price</th>
               <th>Bids</th>
               <th>Status</th>
               <th class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr *ngFor="let auction of auctions()">
                <td class="align-middle text-secondary small py-3">#{{ auction.id }}</td>
                <td class="align-middle py-3">
                  <div class="fw-bold small">{{ auction.title }}</div>
                  <div class="text-secondary x-small">{{ auction.seller }}</div>
                </td>
                <td class="align-middle py-3">
                  <div class="fw-bold text-gold small">₹{{ auction.price }}</div>
                </td>
                <td class="align-middle py-3">
                  <span class="badge bg-gold-soft text-gold px-2 py-0 x-small">{{ auction.bids }} bids</span>
                </td>
                <td class="align-middle py-3">
                  <span class="badge" [class]="auction.status === 'active' ? 'bg-success-soft text-success' : 'bg-warning-soft text-warning'" style="font-size: 0.65rem;">
                    {{ auction.status | uppercase }}
                  </span>
                </td>
                <td class="text-end align-middle py-3">
                  <button class="btn btn-outline-gold btn-sm border-0 me-1"><i class="fas fa-eye"></i></button>
                  <button *ngIf="auction.status === 'pending'" class="btn btn-outline-success btn-sm border-0 me-1"><i class="fas fa-check"></i></button>
                  <button class="btn btn-outline-danger btn-sm border-0"><i class="fas fa-ban"></i></button>
                </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  `
})
export class AdminAuctions {
  auctions = signal([
    { id: 402, title: 'Luxury Villa #12', seller: 'Estate Group', price: '12,40,000', bids: 14, status: 'active' },
    { id: 403, title: 'Vintage Watch #44', seller: 'Anil Kumar', price: '4,500', bids: 4, status: 'pending' },
    { id: 404, title: 'Classic Car #2', seller: 'Auto Masters', price: '8,20,000', bids: 0, status: 'pending' }
  ]);
}
