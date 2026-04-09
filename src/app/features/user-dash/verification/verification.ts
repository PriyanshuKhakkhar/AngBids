import { Component, OnInit, inject, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ReactiveFormsModule, FormBuilder, FormGroup, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { DashboardService } from '../../../core/services/dashboard.service';

@Component({
  selector: 'app-verification',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule],
  template: `
    <div class="glass-panel p-5 h-100">
      <div class="d-flex align-items-center mb-4">
        <div class="p-3 rounded-circle bg-warning bg-opacity-10 text-warning me-3">
            <i class="fas fa-id-card fs-4"></i>
        </div>
        <div>
            <h2 class="display-font h3 mb-1">Identity Verification</h2>
            <p class="text-secondary small mb-0">Complete your profile to unlock premium auction features.</p>
        </div>
      </div>

      <div *ngIf="kycStatus() === 'pending'" class="alert alert-info border-info bg-transparent text-info small mb-4 animate__animated animate__fadeIn">
          <i class="fas fa-clock me-2"></i>Your verification is currently under review. This usually takes 24-48 hours.
      </div>

      <div *ngIf="successMessage()" class="alert alert-success border-success bg-transparent text-success small mb-4">
          <i class="fas fa-check-circle me-2"></i>{{ successMessage() }}
      </div>

      <div *ngIf="kycStatus() !== 'pending' && kycStatus() !== 'approved'" class="kyc-form-container mt-4">
          <form [formGroup]="kycForm" (ngSubmit)="onSubmit()">
              <div class="mb-4">
                  <label class="small text-secondary mb-2 fw-bold">Select Document Type</label>
                  <select class="form-select form-control-elite shadow-none" formControlName="document_type">
                      <option value="">Choose document...</option>
                      <option value="aadhar">Aadhar Card</option>
                      <option value="pan">PAN Card</option>
                      <option value="passport">Passport</option>
                      <option value="voter_id">Voter ID</option>
                  </select>
              </div>

              <div class="mb-5">
                  <label class="small text-secondary mb-3 fw-bold d-block">Upload Document Proof (Front Side)</label>
                  <div class="upload-area p-5 border-2 border-dashed rounded-4 text-center cursor-pointer transition-all"
                       (click)="fileInput.click()"
                       [class.border-primary]="isDragging"
                       (dragover)="onDragOver($event)"
                       (dragleave)="onDragLeave()"
                       (drop)="onDrop($event)">
                      <input type="file" #fileInput (change)="onFileSelected($event)" accept="image/*,.pdf" class="d-none">
                      
                      <div *ngIf="!selectedFile" class="py-3">
                          <i class="fas fa-cloud-upload-alt text-primary fs-1 mb-3"></i>
                          <h6 class="text-dark">Click or drag to upload</h6>
                          <p class="text-secondary small">PNG, JPG or PDF up to 5MB</p>
                      </div>

                      <div *ngIf="selectedFile" class="py-3 d-flex align-items-center justify-content-center">
                          <div class="p-3 bg-primary bg-opacity-10 rounded-3 text-primary d-flex align-items-center">
                              <i class="fas fa-file-alt fs-4 me-3"></i>
                              <div class="text-start">
                                  <div class="small fw-bold">{{ selectedFile.name }}</div>
                                  <div class="x-small">{{ (selectedFile.size / 1024 / 1024).toFixed(2) }} MB</div>
                              </div>
                              <button type="button" class="btn btn-sm text-danger ms-4" (click)="removeFile($event)"><i class="fas fa-times"></i></button>
                          </div>
                      </div>
                  </div>
              </div>

              <button type="submit" class="btn btn-gold px-5 py-3 fw-bold w-100 w-md-auto" 
                      [disabled]="isLoading() || kycForm.invalid || !selectedFile">
                  <span *ngIf="!isLoading()">SUBMIT FOR VERIFICATION</span>
                  <span *ngIf="isLoading()">
                      <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                      SUBMITTING...
                  </span>
              </button>
          </form>
      </div>

      <div *ngIf="kycStatus() === 'approved'" class="text-center py-5">
          <div class="p-4 rounded-circle bg-success bg-opacity-10 text-success d-inline-block mb-4">
              <i class="fas fa-check-double fs-1"></i>
          </div>
          <h4 class="text-dark">Verification Complete!</h4>
          <p class="text-secondary">Your account is fully verified. You can now bid without restrictions.</p>
          <button class="btn btn-outline-primary mt-3" (click)="goBack()">Return to Profile</button>
      </div>
    </div>
  `,
  styles: [`
    .upload-area {
        background: rgba(78, 115, 223, 0.02);
        border-color: #cbd5e0 !important;
    }
    .upload-area:hover {
        background: rgba(78, 115, 223, 0.05);
        border-color: #4e73df !important;
    }
    .border-dashed {
        border-style: dashed !important;
    }
    .cursor-pointer {
        cursor: pointer;
    }
  `]
})
export class Verification implements OnInit {
  private fb = inject(FormBuilder);
  private dashboardService = inject(DashboardService);
  private router = inject(Router);

  kycForm: FormGroup;
  isLoading = signal(false);
  isDragging = false;
  selectedFile: File | null = null;
  kycStatus = signal<string | null>(null);
  successMessage = signal<string | null>(null);

  constructor() {
    this.kycForm = this.fb.group({
      document_type: ['', Validators.required]
    });
  }

  ngOnInit(): void {
    this.loadStatus();
  }

  loadStatus() {
    this.dashboardService.getKycStatus().subscribe({
      next: (res) => {
        if (res.data) this.kycStatus.set(res.data.status);
      }
    });
  }

  onFileSelected(event: any) {
    const file = event.target.files[0];
    if (file) {
      this.selectedFile = file;
    }
  }

  removeFile(event: Event) {
    event.stopPropagation();
    this.selectedFile = null;
  }

  onDragOver(event: DragEvent) {
    event.preventDefault();
    this.isDragging = true;
  }

  onDragLeave() {
    this.isDragging = false;
  }

  onDrop(event: DragEvent) {
    event.preventDefault();
    this.isDragging = false;
    const file = event.dataTransfer?.files[0];
    if (file) {
      this.selectedFile = file;
    }
  }

  onSubmit() {
    if (this.kycForm.invalid || !this.selectedFile) return;

    this.isLoading.set(true);
    const formData = new FormData();
    formData.append('document_type', this.kycForm.value.document_type);
    formData.append('document', this.selectedFile);

    this.dashboardService.submitKyc(formData).subscribe({
      next: (res) => {
        this.isLoading.set(false);
        this.successMessage.set('Verification document submitted successfully!');
        this.kycStatus.set('pending');
      },
      error: (err) => {
        this.isLoading.set(false);
        alert(err.message);
      }
    });
  }

  goBack() {
    this.router.navigate(['/dashboard/profile']);
  }
}
