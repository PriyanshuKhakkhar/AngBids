import { Component, OnInit, inject, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ReactiveFormsModule, FormBuilder, FormGroup, Validators, AbstractControl, ValidationErrors } from '@angular/forms';
import { Router } from '@angular/router';
import { CategoryService } from '../../../core/services/category.service';
import { UserAuctionService } from '../../../core/services/user-auction.service';
import { Category } from '../../../core/models/home.model';

/**
 * Custom validator: end_time must be after start_time.
 */
function endAfterStartValidator(control: AbstractControl): ValidationErrors | null {
  const start = control.get('start_time')?.value;
  const end = control.get('end_time')?.value;
  if (start && end && new Date(end) <= new Date(start)) {
    return { endBeforeStart: true };
  }
  return null;
}

@Component({
  selector: 'app-create-auction',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule],
  template: `
    <div class="glass-panel p-4 p-lg-5 h-100">

      <!-- Page Header -->
      <div class="d-flex align-items-center mb-4">
        <div class="p-3 rounded-circle bg-primary bg-opacity-10 text-primary me-3">
          <i class="fas fa-plus-circle fs-4"></i>
        </div>
        <div>
          <h2 class="display-font h3 mb-1">List an Auction</h2>
          <p class="text-secondary small mb-0">
            Submit your item — a moderator will review and approve it before it goes live.
          </p>
        </div>
      </div>

      <!-- Success Banner -->
      <div *ngIf="successMessage()"
           class="alert alert-success d-flex align-items-start gap-3 rounded-4 border-0 mb-4 p-4" role="alert">
        <i class="fas fa-check-circle fs-5 mt-1"></i>
        <div>
          <div class="fw-bold mb-1">Submission Received!</div>
          <div class="small">{{ successMessage() }}</div>
          <div class="mt-3">
            <button class="btn btn-success btn-sm rounded-pill px-3 fw-bold"
                    (click)="goToMyListings()">
              <i class="fas fa-list me-2"></i>View My Listings
            </button>
          </div>
        </div>
      </div>

      <!-- Error Banner -->
      <div *ngIf="errorMessage()"
           class="alert alert-danger d-flex align-items-start gap-3 rounded-4 border-0 mb-4 p-4" role="alert">
        <i class="fas fa-exclamation-triangle fs-5 mt-1"></i>
        <div>
          <div class="fw-bold mb-1">Submission Failed</div>
          <div class="small">{{ errorMessage() }}</div>
        </div>
      </div>

      <!-- Form -->
      <form [formGroup]="auctionForm" (ngSubmit)="onSubmit()" *ngIf="!successMessage()">
        <div class="row g-4">

          <!-- Left Column: Details -->
          <div class="col-md-7">
            <div class="mb-3">
              <label class="form-label fw-semibold small text-secondary">
                Auction Title <span class="text-danger">*</span>
              </label>
              <input type="text"
                     class="form-control"
                     formControlName="title"
                     placeholder="e.g. Vintage Rolex Datejust 36mm (1987)"
                     [class.is-invalid]="isInvalid('title')">
              <div class="invalid-feedback" *ngIf="isInvalid('title')">
                Title is required (minimum 3 characters).
              </div>
            </div>

            <div class="row g-3 mb-3">
              <div class="col-md-6">
                <label class="form-label fw-semibold small text-secondary">
                  Category <span class="text-danger">*</span>
                </label>
                <select class="form-select"
                        formControlName="category_id"
                        [class.is-invalid]="isInvalid('category_id')">
                  <option value="">— Select Category —</option>
                  <option *ngFor="let cat of categories()" [value]="cat.id">{{ cat.name }}</option>
                </select>
                <div class="invalid-feedback" *ngIf="isInvalid('category_id')">Please select a category.</div>
              </div>

              <div class="col-md-6">
                <label class="form-label fw-semibold small text-secondary">
                  Starting Price (₹) <span class="text-danger">*</span>
                </label>
                <input type="number"
                       class="form-control"
                       formControlName="starting_price"
                       placeholder="Minimum ₹100"
                       min="100"
                       [class.is-invalid]="isInvalid('starting_price')">
                <div class="invalid-feedback" *ngIf="isInvalid('starting_price')">
                  Starting price must be at least ₹100.
                </div>
              </div>
            </div>

            <div class="row g-3 mb-3">
              <div class="col-md-6">
                <label class="form-label fw-semibold small text-secondary">
                  Bid Increment (₹)
                </label>
                <input type="number"
                       class="form-control"
                       formControlName="min_increment"
                       placeholder="e.g. 50"
                       min="1">
              </div>

              <div class="col-md-6">
                <label class="form-label fw-semibold small text-secondary">
                  Reserve Price (₹) <span class="text-muted x-small">(optional)</span>
                </label>
                <input type="number"
                       class="form-control"
                       formControlName="reserve_price"
                       placeholder="Hidden minimum price"
                       min="0">
              </div>
            </div>

            <div class="row g-3 mb-3">
              <div class="col-md-6">
                <label class="form-label fw-semibold small text-secondary">
                  Start Date & Time <span class="text-danger">*</span>
                </label>
                <input type="datetime-local"
                       class="form-control"
                       formControlName="start_time"
                       [class.is-invalid]="isInvalid('start_time')">
                <div class="invalid-feedback" *ngIf="isInvalid('start_time')">Start time is required.</div>
              </div>

              <div class="col-md-6">
                <label class="form-label fw-semibold small text-secondary">
                  End Date & Time <span class="text-danger">*</span>
                </label>
                <input type="datetime-local"
                       class="form-control"
                       formControlName="end_time"
                       [class.is-invalid]="isInvalid('end_time') || auctionForm.hasError('endBeforeStart')">
                <div class="invalid-feedback" *ngIf="isInvalid('end_time')">End time is required.</div>
              </div>
            </div>
            <div *ngIf="auctionForm.hasError('endBeforeStart') && auctionForm.get('end_time')?.touched"
                 class="text-danger x-small mb-3">
              <i class="fas fa-exclamation-circle me-1"></i>End time must be after the start time.
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold small text-secondary">Location <span class="text-muted x-small">(optional)</span></label>
              <input type="text"
                     class="form-control"
                     formControlName="location"
                     placeholder="e.g. Mumbai, Maharashtra">
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold small text-secondary">
                Description <span class="text-danger">*</span>
              </label>
              <textarea class="form-control"
                        formControlName="description"
                        rows="5"
                        placeholder="Describe your item clearly — condition, history, what's included..."
                        [class.is-invalid]="isInvalid('description')"></textarea>
              <div class="invalid-feedback" *ngIf="isInvalid('description')">
                Description is required (minimum 20 characters).
              </div>
              <div class="text-muted x-small mt-1 text-end">
                {{ auctionForm.get('description')?.value?.length || 0 }}/5000
              </div>
            </div>
          </div>

          <!-- Right Column: Image Upload -->
          <div class="col-md-5">
            <label class="form-label fw-semibold small text-secondary d-block">
              Item Images <span class="text-danger">*</span>
              <span class="text-muted fw-normal"> (at least 1, max 5)</span>
            </label>

            <!-- Preview Grid -->
            <div class="image-grid mb-3" *ngIf="imagePreviews().length > 0">
              <div *ngFor="let preview of imagePreviews(); let i = index"
                   class="img-preview-wrap position-relative rounded-3 overflow-hidden border"
                   style="height: 120px;">
                <img [src]="preview" class="w-100 h-100" style="object-fit: cover;">
                <button type="button"
                        class="btn btn-danger btn-sm rounded-circle p-0 position-absolute top-0 end-0 m-1 remove-img-btn shadow"
                        (click)="removeImage(i)">
                  <i class="fas fa-times" style="font-size: 0.65rem; line-height: 1.4;"></i>
                </button>
              </div>
            </div>

            <!-- Upload Zone -->
            <div class="upload-zone rounded-4 border-2 border-dashed p-4 text-center"
                 [class.border-danger]="submitAttempted && imagePreviews().length === 0"
                 [class.border-primary]="imagePreviews().length > 0"
                 (click)="fileInput.click()"
                 style="cursor: pointer; background: #f8f9fc; border-style: dashed;">
              <i class="fas fa-cloud-upload-alt fs-2 text-secondary mb-2 d-block"></i>
              <span class="small fw-semibold text-secondary">Click to upload images</span><br>
              <span class="x-small text-muted">JPG, PNG, WEBP — Max 2MB each</span>
              <input type="file"
                     #fileInput
                     multiple
                     accept="image/jpeg,image/png,image/jpg,image/gif"
                     class="d-none"
                     (change)="onFilesSelected($event)">
            </div>

            <div *ngIf="submitAttempted && imagePreviews().length === 0"
                 class="text-danger x-small mt-1">
              <i class="fas fa-exclamation-circle me-1"></i>At least one image is required.
            </div>

            <!-- Approval Notice -->
            <div class="mt-4 p-3 rounded-4 bg-warning bg-opacity-10 border border-warning border-opacity-25">
              <div class="d-flex align-items-start gap-2">
                <i class="fas fa-shield-alt text-warning mt-1"></i>
                <div class="small">
                  <div class="fw-bold text-dark mb-1">Admin Approval Required</div>
                  <div class="text-secondary">Your auction will be reviewed before it becomes publicly visible.
                  You can track its status in <strong>My Listings</strong>.</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Actions -->
        <div class="mt-5 pt-4 border-top d-flex gap-3 justify-content-end align-items-center">
          <button type="button"
                  class="btn btn-link text-secondary text-decoration-none fw-semibold"
                  (click)="goBack()">
            Cancel
          </button>
          <button type="submit"
                  class="btn btn-primary px-5 py-2 fw-bold rounded-pill"
                  [disabled]="isLoading()">
            <span *ngIf="!isLoading()">
              <i class="fas fa-paper-plane me-2"></i>Submit for Approval
            </span>
            <span *ngIf="isLoading()" class="d-flex align-items-center gap-2">
              <span class="spinner-border spinner-border-sm" role="status"></span>
              Submitting...
            </span>
          </button>
        </div>
      </form>
    </div>
  `,
  styles: [`
    .x-small { font-size: 0.75rem; }
    .image-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
      gap: 8px;
    }
    .remove-img-btn {
      width: 22px;
      height: 22px;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .upload-zone:hover {
      border-color: #0d6efd !important;
      background: #eef3ff !important;
    }
    .border-dashed { border-style: dashed !important; }
  `]
})
export class CreateAuction implements OnInit {
  private fb = inject(FormBuilder);
  private router = inject(Router);
  private categoryService = inject(CategoryService);
  private userAuctionService = inject(UserAuctionService);

  auctionForm: FormGroup;
  categories = signal<Category[]>([]);
  isLoading = signal(false);
  imagePreviews = signal<string[]>([]);
  selectedFiles: File[] = [];
  successMessage = signal<string | null>(null);
  errorMessage = signal<string | null>(null);
  submitAttempted = false;

  constructor() {
    this.auctionForm = this.fb.group({
      title: ['', [Validators.required, Validators.minLength(3), Validators.maxLength(100)]],
      category_id: ['', Validators.required],
      starting_price: ['', [Validators.required, Validators.min(100)]],
      min_increment: [''],
      reserve_price: [''],
      start_time: ['', Validators.required],
      end_time: ['', Validators.required],
      location: [''],
      description: ['', [Validators.required, Validators.minLength(20), Validators.maxLength(5000)]],
    }, { validators: endAfterStartValidator });
  }

  ngOnInit(): void {
    this.categoryService.getCategories().subscribe({
      next: (res) => this.categories.set(res),
      error: (err) => console.error('Failed to load categories', err)
    });
  }

  isInvalid(field: string): boolean {
    const control = this.auctionForm.get(field);
    return !!(control?.invalid && (control.touched || control.dirty));
  }

  onFilesSelected(event: Event): void {
    const input = event.target as HTMLInputElement;
    if (!input.files) return;

    const newFiles = Array.from(input.files);
    const combined = [...this.selectedFiles, ...newFiles];

    if (combined.length > 5) {
      this.errorMessage.set('You can upload a maximum of 5 images.');
      return;
    }

    for (const file of newFiles) {
      if (file.size > 2 * 1024 * 1024) {
        this.errorMessage.set(`"${file.name}" exceeds 2MB. Please choose a smaller image.`);
        return;
      }
      const reader = new FileReader();
      reader.onload = (e: any) => {
        this.imagePreviews.update(prev => [...prev, e.target.result]);
      };
      reader.readAsDataURL(file);
    }

    this.selectedFiles = combined;
    this.errorMessage.set(null);
    // reset the input so the same file can be re-added if removed
    input.value = '';
  }

  removeImage(index: number): void {
    this.selectedFiles = this.selectedFiles.filter((_, i) => i !== index);
    this.imagePreviews.update(prev => prev.filter((_, i) => i !== index));
  }

  onSubmit(): void {
    this.submitAttempted = true;
    this.auctionForm.markAllAsTouched();

    if (this.auctionForm.invalid || this.selectedFiles.length === 0) {
      if (this.selectedFiles.length === 0) {
        this.errorMessage.set('Please upload at least one image.');
      }
      return;
    }

    this.isLoading.set(true);
    this.errorMessage.set(null);

    const val = this.auctionForm.value;

    this.userAuctionService.createAuction({
      title: val.title,
      description: val.description,
      category_id: Number(val.category_id),
      starting_price: Number(val.starting_price),
      start_time: val.start_time,
      end_time: val.end_time,
      min_increment: val.min_increment ? Number(val.min_increment) : undefined,
      reserve_price: val.reserve_price ? Number(val.reserve_price) : undefined,
      location: val.location || undefined,
      images: this.selectedFiles
    }).subscribe({
      next: () => {
        this.isLoading.set(false);
        this.successMessage.set(
          'Your auction has been submitted and is waiting for admin approval. ' +
          'You will be notified once it is reviewed.'
        );
        this.auctionForm.reset();
        this.selectedFiles = [];
        this.imagePreviews.set([]);
      },
      error: (err: Error) => {
        this.isLoading.set(false);
        this.errorMessage.set(err.message);
      }
    });
  }

  goToMyListings(): void {
    this.router.navigate(['/dashboard/my-listings']);
  }

  goBack(): void {
    this.router.navigate(['/dashboard']);
  }
}
