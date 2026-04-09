import { Component, OnInit, inject, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ReactiveFormsModule, FormBuilder, FormGroup, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { CategoryService } from '../../../core/services/category.service';
import { Category } from '../../../core/models/home.model';

@Component({
  selector: 'app-create-auction',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule],
  template: `
    <div class="glass-panel p-5 h-100">
      <div class="d-flex align-items-center mb-4">
        <div class="p-3 rounded-circle bg-primary bg-opacity-10 text-primary me-3">
            <i class="fas fa-plus-circle fs-4"></i>
        </div>
        <div>
            <h2 class="display-font h3 mb-1">Create Auction</h2>
            <p class="text-secondary small mb-0">List your item and start receiving bids</p>
        </div>
      </div>

      <div *ngIf="successMessage()" class="alert alert-success border-success bg-transparent text-success small mb-4 animate__animated animate__fadeIn">
          <i class="fas fa-check-circle me-2"></i>{{ successMessage() }}
      </div>

      <div *ngIf="errorMessage()" class="alert alert-danger border-danger bg-transparent text-danger small mb-4 animate__animated animate__fadeIn">
          <i class="fas fa-exclamation-triangle me-2"></i>{{ errorMessage() }}
      </div>

      <form [formGroup]="auctionForm" (ngSubmit)="onSubmit()" class="mt-4">
          <div class="row g-4">
              <!-- Left Column: Basic Info -->
              <div class="col-md-7">
                  <div class="mb-4">
                      <label class="small text-secondary mb-2 fw-bold">Auction Title</label>
                      <input type="text" class="form-control form-control-elite" formControlName="title" placeholder="e.g. Vintage Rolex Datejust 36mm">
                      <div *ngIf="auctionForm.get('title')?.touched && auctionForm.get('title')?.invalid" class="text-danger x-small mt-1">
                          Title is required (min 5 characters).
                      </div>
                  </div>

                  <div class="row g-3 mb-4">
                      <div class="col-md-6">
                          <label class="small text-secondary mb-2 fw-bold">Category</label>
                          <select class="form-select form-control-elite" formControlName="category_id">
                              <option value="">Select Category</option>
                              <option *ngFor="let cat of categories()" [value]="cat.id">{{ cat.name }}</option>
                          </select>
                      </div>
                      <div class="col-md-6">
                          <label class="small text-secondary mb-2 fw-bold">Starting Price ($)</label>
                          <input type="number" class="form-control form-control-elite" formControlName="starting_price" placeholder="0.00">
                      </div>
                  </div>

                  <div class="mb-4">
                      <label class="small text-secondary mb-2 fw-bold">End Date & Time</label>
                      <input type="datetime-local" class="form-control form-control-elite" formControlName="end_date">
                  </div>

                  <div class="mb-0">
                      <label class="small text-secondary mb-2 fw-bold">Description</label>
                      <textarea class="form-control form-control-elite" formControlName="description" rows="5" placeholder="Tell bidders about your item..."></textarea>
                  </div>
              </div>

              <!-- Right Column: Image Upload & Preview -->
              <div class="col-md-5">
                  <label class="small text-secondary mb-2 fw-bold d-block">Item Image</label>
                  <div class="image-upload-wrapper">
                      <div class="image-preview mb-3 rounded-4 border d-flex align-items-center justify-content-center overflow-hidden position-relative"
                           style="height: 300px; background: #f8f9fc;">
                          
                          <img *ngIf="imagePreview()" [src]="imagePreview()" class="w-100 h-100 object-fit-cover">
                          
                          <div *ngIf="!imagePreview()" class="text-center text-secondary opacity-50">
                              <i class="fas fa-image fs-1 mb-2"></i>
                              <p class="small mb-0">Preview will appear here</p>
                          </div>

                          <button *ngIf="imagePreview()" type="button" class="btn btn-danger btn-sm rounded-circle position-absolute top-0 end-0 m-3 shadow" 
                                  (click)="removeImage($event)">
                              <i class="fas fa-times"></i>
                          </button>
                      </div>

                      <div class="upload-btn-wrapper w-100">
                          <button type="button" class="btn btn-outline-primary w-100 py-3 rounded-4 border-2 border-dashed shadow-none" 
                                  (click)="fileInput.click()">
                              <i class="fas fa-cloud-upload-alt me-2"></i> Choose Image
                          </button>
                          <input type="file" #fileInput (change)="onFileSelected($event)" accept="image/*" class="d-none">
                      </div>
                      <small class="text-muted x-small mt-2 d-block text-center">Supported: JPG, PNG, WEBP (Max 5MB)</small>
                  </div>
              </div>
          </div>

          <div class="mt-5 pt-4 border-top d-flex gap-3 justify-content-end">
              <button type="button" class="btn btn-link text-secondary text-decoration-none fw-bold" (click)="goBack()">CANCEL</button>
              <button type="submit" class="btn btn-gold px-5 py-3 fw-bold" [disabled]="isLoading() || auctionForm.invalid">
                  <span *ngIf="!isLoading()">CREATE LISTING</span>
                  <span *ngIf="isLoading()">
                      <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                      PROCESSING...
                  </span>
              </button>
          </div>
      </form>
    </div>
  `,
  styles: [`
    .object-fit-cover {
        object-fit: cover;
    }
    .image-preview {
        background-image: radial-gradient(#cbd5e0 1px, transparent 1px);
        background-size: 20px 20px;
    }
    .x-small { font-size: 0.75rem; }
  `]
})
export class CreateAuction implements OnInit {
  private fb = inject(FormBuilder);
  private router = inject(Router);
  private categoryService = inject(CategoryService);

  auctionForm: FormGroup;
  categories = signal<Category[]>([]);
  isLoading = signal(false);
  imagePreview = signal<string | null>(null);
  selectedFile: File | null = null;
  successMessage = signal<string | null>(null);
  errorMessage = signal<string | null>(null);

  constructor() {
    this.auctionForm = this.fb.group({
      title: ['', [Validators.required, Validators.minLength(5)]],
      category_id: ['', Validators.required],
      starting_price: ['', [Validators.required, Validators.min(1)]],
      description: ['', [Validators.required, Validators.minLength(20)]],
      end_date: ['', Validators.required],
    });
  }

  ngOnInit(): void {
    this.loadCategories();
  }

  loadCategories() {
    this.categoryService.getCategories().subscribe({
      next: (res) => this.categories.set(res),
      error: (err) => console.error('Failed to load categories', err)
    });
  }

  onFileSelected(event: any) {
    const file = event.target.files[0];
    if (file) {
      if (file.size > 5 * 1024 * 1024) {
          alert('File size exceeds 5MB');
          return;
      }
      this.selectedFile = file;
      const reader = new FileReader();
      reader.onload = () => {
        this.imagePreview.set(reader.result as string);
      };
      reader.readAsDataURL(file);
    }
  }

  removeImage(event: Event) {
    event.stopPropagation();
    this.selectedFile = null;
    this.imagePreview.set(null);
  }

  onSubmit() {
    if (this.auctionForm.invalid) return;

    this.isLoading.set(true);
    this.successMessage.set(null);
    this.errorMessage.set(null);

    // Mock handling
    setTimeout(() => {
        this.isLoading.set(false);
        this.successMessage.set('Auction created successfully! Redirecting...');
        setTimeout(() => this.router.navigate(['/dashboard/watchlist']), 2000);
    }, 1500);
  }

  goBack() {
    this.router.navigate(['/dashboard']);
  }
}
