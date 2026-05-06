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
    <div class="glass-panel p-4 p-md-5 h-100">
      <div class="d-flex align-items-center mb-4">
        <div class="p-3 rounded-circle bg-warning bg-opacity-10 text-warning me-3">
            <i class="fas fa-id-card fs-4"></i>
        </div>
        <div>
            <h2 class="display-font h3 mb-1">Identity Verification</h2>
            <p class="text-secondary small mb-0">Complete your profile to unlock premium auction features.</p>
        </div>
      </div>

      <div *ngIf="kycStatus() === 'pending' || kycStatus() === 'submitted'" class="alert alert-info border-info bg-transparent text-info small mb-4 animate__animated animate__fadeIn">
          <i class="fas fa-clock me-2"></i>Your verification is currently under review. This usually takes 24-48 hours.
      </div>

      <div *ngIf="successMessage()" class="alert alert-success border-success bg-transparent text-success small mb-4">
          <i class="fas fa-check-circle me-2"></i>{{ successMessage() }}
      </div>

      <div *ngIf="kycStatus() !== 'pending' && kycStatus() !== 'submitted' && kycStatus() !== 'approved'" class="kyc-form-container mt-4">
          <form [formGroup]="kycForm" (ngSubmit)="onSubmit()">
              
              <!-- Section 1: Personal Details -->
              <div class="mb-4 pt-3 border-top border-white border-opacity-10">
                  <h5 class="h6 fw-bold mb-3 text-gold">1. Personal Information</h5>
                  <div class="row g-3">
                      <div class="col-md-6">
                          <label class="small text-secondary mb-1">Full Name (As per ID)</label>
                          <input type="text" class="form-control form-control-elite" formControlName="full_name" placeholder="John Doe">
                      </div>
                      <div class="col-md-6">
                          <label class="small text-secondary mb-1">Date of Birth</label>
                          <input type="date" class="form-control form-control-elite" formControlName="date_of_birth">
                      </div>
                      <div class="col-12">
                          <label class="small text-secondary mb-1">Residential Address</label>
                          <textarea class="form-control form-control-elite" formControlName="address" rows="2" placeholder="Street, City, State, ZIP"></textarea>
                      </div>
                  </div>
              </div>

              <!-- Section 2: ID Document Details -->
              <div class="mb-4 pt-3 border-top border-white border-opacity-10">
                  <h5 class="h6 fw-bold mb-3 text-gold">2. ID Document Details</h5>
                  <div class="row g-3">
                      <div class="col-md-6">
                          <label class="small text-secondary mb-1">ID Type</label>
                          <select class="form-select form-control-elite shadow-none" formControlName="id_type">
                              <option value="">Choose document...</option>
                              <option value="aadhaar">Aadhar Card</option>
                              <option value="pan">PAN Card</option>
                              <option value="passport">Passport</option>
                              <option value="driving_license">Driving License</option>
                          </select>
                      </div>
                      <div class="col-md-6">
                          <label class="small text-secondary mb-1">ID Number</label>
                          <input type="text" class="form-control form-control-elite" formControlName="id_number" placeholder="Enter ID number">
                      </div>
                  </div>
              </div>

              <!-- Section 3: Document Uploads -->
              <div class="mb-5 pt-3 border-top border-white border-opacity-10">
                  <h5 class="h6 fw-bold mb-3 text-gold">3. Proof Uploads</h5>
                  <div class="row g-4">
                      <!-- ID Document -->
                      <div class="col-md-6">
                          <label class="small text-secondary mb-2 fw-bold d-block">ID Document Proof (Front Side)</label>
                          <div class="upload-area p-4 border-2 border-dashed rounded-4 text-center cursor-pointer transition-all"
                               (click)="idFileInput.click()">
                              <input type="file" #idFileInput (change)="onIdFileSelected($event)" accept="image/*,.pdf" class="d-none">
                              
                              <div *ngIf="!idFile" class="py-2">
                                  <i class="fas fa-file-upload text-primary fs-3 mb-2"></i>
                                  <p class="mb-0 small fw-bold">Upload ID</p>
                                  <p class="text-secondary x-small mb-0">PDF, JPG up to 2MB</p>
                              </div>

                              <div *ngIf="idFile" class="py-2">
                                  <div class="d-flex align-items-center justify-content-center text-primary">
                                      <i class="fas fa-check-circle me-2"></i>
                                      <span class="small fw-bold text-truncate" style="max-width: 150px;">{{ idFile.name }}</span>
                                      <i class="fas fa-times text-danger ms-2" (click)="removeIdFile($event)"></i>
                                  </div>
                              </div>
                          </div>
                      </div>

                      <!-- Selfie Image -->
                      <div class="col-md-6">
                          <label class="small text-secondary mb-2 fw-bold d-block">Selfie with ID</label>
                          <div class="upload-area p-4 border-2 border-dashed rounded-4 text-center cursor-pointer transition-all"
                               (click)="selfieFileInput.click()">
                              <input type="file" #selfieFileInput (change)="onSelfieFileSelected($event)" accept="image/*" class="d-none">
                              
                              <div *ngIf="!selfieFile" class="py-2">
                                  <i class="fas fa-camera text-primary fs-3 mb-2"></i>
                                  <p class="mb-0 small fw-bold">Upload Selfie</p>
                                  <p class="text-secondary x-small mb-0">JPG, PNG up to 2MB</p>
                              </div>

                              <div *ngIf="selfieFile" class="py-2">
                                  <div class="d-flex align-items-center justify-content-center text-primary">
                                      <i class="fas fa-check-circle me-2"></i>
                                      <span class="small fw-bold text-truncate" style="max-width: 150px;">{{ selfieFile.name }}</span>
                                      <i class="fas fa-times text-danger ms-2" (click)="removeSelfieFile($event)"></i>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>

              <button type="submit" class="btn btn-gold px-5 py-3 fw-bold w-100" 
                      [disabled]="isLoading() || kycForm.invalid || !idFile || !selfieFile">
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
        min-height: 120px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
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
    .x-small {
        font-size: 0.65rem;
    }
    .form-control-elite {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        color: #333;
    }
    .form-control-elite:focus {
        background: rgba(255,255,255,0.08);
        border-color: var(--gold-color, #d4af37);
        box-shadow: none;
    }
  `]
})
export class Verification implements OnInit {
  private fb = inject(FormBuilder);
  private dashboardService = inject(DashboardService);
  private router = inject(Router);

  kycForm: FormGroup;
  isLoading = signal(false);
  
  idFile: File | null = null;
  selfieFile: File | null = null;
  
  kycStatus = signal<string | null>(null);
  successMessage = signal<string | null>(null);

  constructor() {
    this.kycForm = this.fb.group({
      full_name: ['', [Validators.required, Validators.minLength(3)]],
      date_of_birth: ['', Validators.required],
      address: ['', Validators.required],
      id_type: ['', Validators.required],
      id_number: ['', [Validators.required, Validators.minLength(5)]]
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

  onIdFileSelected(event: any) {
    const file = event.target.files[0];
    if (file) this.idFile = file;
  }

  onSelfieFileSelected(event: any) {
    const file = event.target.files[0];
    if (file) this.selfieFile = file;
  }

  removeIdFile(event: Event) {
    event.stopPropagation();
    this.idFile = null;
  }

  removeSelfieFile(event: Event) {
    event.stopPropagation();
    this.selfieFile = null;
  }

  onSubmit() {
    if (this.kycForm.invalid || !this.idFile || !this.selfieFile) return;

    this.isLoading.set(true);
    const formData = new FormData();
    
    // Append form fields
    Object.keys(this.kycForm.value).forEach(key => {
      formData.append(key, this.kycForm.value[key]);
    });
    
    // Append files
    formData.append('id_document', this.idFile);
    formData.append('selfie_image', this.selfieFile);

    this.dashboardService.submitKyc(formData).subscribe({
      next: (res) => {
        this.isLoading.set(false);
        this.successMessage.set('Verification documents submitted successfully!');
        this.kycStatus.set('pending');
        window.scrollTo({ top: 0, behavior: 'smooth' });
      },
      error: (err) => {
        this.isLoading.set(false);
        alert(err.message || 'Failed to submit KYC. Please check your data and try again.');
      }
    });
  }

  goBack() {
    this.router.navigate(['/dashboard/profile']);
  }
}
