import { Component, OnInit, inject, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { AdminService } from '../../services/admin.service';

@Component({
  selector: 'app-kyc-management',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './kyc-management.html',
  styleUrls: ['./kyc-management.css']
})
export class KycManagementComponent implements OnInit {
  private adminService = inject(AdminService);

  kycRequests = signal<any[]>([]);
  isLoading = signal(false);
  selectedKyc = signal<any>(null);
  showModal = signal(false);
  
  // Rejection logic
  rejectReason = signal('');
  isProcessing = signal(false);

  // Filters
  statusFilter = signal('pending');

  ngOnInit(): void {
    this.loadRequests();
  }

  loadRequests() {
    this.isLoading.set(true);
    this.adminService.getKycRequests({ status: this.statusFilter() }).subscribe({
      next: (res) => {
        // Handle paginated response structure
        const data = res.data?.data || res.data || res;
        this.kycRequests.set(Array.isArray(data) ? data : []);
        this.isLoading.set(false);
      },
      error: () => this.isLoading.set(false)
    });
  }

  onFilterChange(status: string) {
    this.statusFilter.set(status);
    this.loadRequests();
  }

  openReview(kyc: any) {
    this.selectedKyc.set(kyc);
    this.showModal.set(true);
    this.rejectReason.set('');
  }

  closeModal() {
    this.showModal.set(false);
    this.selectedKyc.set(null);
  }

  openDocument(url: string) {
    if (url) window.open(url, '_blank');
  }

  approve() {
    if (!this.selectedKyc()) return;
    
    this.isProcessing.set(true);
    this.adminService.updateKycStatus(this.selectedKyc().id, 'approved').subscribe({
      next: () => {
        this.isProcessing.set(false);
        this.closeModal();
        this.loadRequests();
      },
      error: (err) => {
        this.isProcessing.set(false);
        alert(err.message || 'Failed to approve KYC');
      }
    });
  }

  reject() {
    if (!this.selectedKyc() || !this.rejectReason()) {
      alert('Please provide a rejection reason');
      return;
    }

    this.isProcessing.set(true);
    this.adminService.updateKycStatus(this.selectedKyc().id, 'rejected', this.rejectReason()).subscribe({
      next: () => {
        this.isProcessing.set(false);
        this.closeModal();
        this.loadRequests();
      },
      error: (err) => {
        this.isProcessing.set(false);
        alert(err.message || 'Failed to reject KYC');
      }
    });
  }

  getStatusClass(status: string) {
    switch (status) {
      case 'approved': return 'bg-success-soft text-success';
      case 'rejected': return 'bg-danger-soft text-danger';
      case 'pending': return 'bg-warning-soft text-warning';
      default: return 'bg-secondary-soft text-secondary';
    }
  }
}
