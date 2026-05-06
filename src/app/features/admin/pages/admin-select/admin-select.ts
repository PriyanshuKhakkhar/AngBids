import { Component, inject } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterLink, Router } from '@angular/router';
import { AuthService } from '../../../../core/services/auth.service';

@Component({
  selector: 'app-admin-select',
  standalone: true,
  imports: [CommonModule, RouterLink],
  template: `
    <div class="min-vh-100 d-flex flex-column bg-light">
      <!-- Navbar / Header Area -->
      <header class="p-4 d-flex justify-content-between align-items-center">
        <a routerLink="/" class="text-decoration-none">
          <h4 class="m-0 text-primary fw-bold d-flex align-items-center gap-2">
            <i class="fas fa-gavel"></i> AngBids
          </h4>
        </a>
        <button class="btn btn-outline-danger btn-sm rounded-pill px-4 fw-semibold" (click)="onLogout()">
          Logout
        </button>
      </header>

      <!-- Main Selection Area -->
      <main class="flex-grow-1 d-flex align-items-center justify-content-center p-4">
        <div class="col-12 col-md-10 col-lg-8" style="max-width: 800px;">
          <div class="text-center mb-5">
            <div class="d-inline-flex align-items-center justify-content-center p-3 rounded-circle bg-primary bg-opacity-10 text-primary mb-3">
              <i class="fas fa-user-shield fs-1"></i>
            </div>
            <h1 class="h2 fw-bold text-dark mb-2">Welcome Back, Admin</h1>
            <p class="text-secondary fs-5">Where would you like to go today?</p>
          </div>

          <div class="row g-4 justify-content-center">
            <!-- User / Client Side Flow -->
            <div class="col-md-6">
              <a routerLink="/dashboard" class="card h-100 border-0 shadow-sm rounded-4 text-decoration-none transition-all hover-translate-y selection-card">
                <div class="card-body p-5 text-center d-flex flex-column align-items-center">
                  <div class="p-4 rounded-circle bg-success bg-opacity-10 text-success mb-4 flex-shrink-0">
                    <i class="fas fa-store fs-2"></i>
                  </div>
                  <h3 class="h4 fw-bold text-dark mb-3">View Client Side</h3>
                  <p class="text-secondary mb-0">Browse auctions, place bids, and view your personal user dashboard.</p>
                </div>
              </a>
            </div>

            <!-- Admin Panel Flow -->
            <div class="col-md-6">
              <a routerLink="/admin" class="card h-100 shadow-sm rounded-4 text-decoration-none transition-all hover-translate-y selection-card border-primary" style="border-width: 2px !important;">
                <div class="card-body p-5 text-center d-flex flex-column align-items-center">
                  <div class="p-4 rounded-circle bg-primary bg-opacity-10 text-primary mb-4 flex-shrink-0">
                    <i class="fas fa-sliders-h fs-2"></i>
                  </div>
                  <h3 class="h4 fw-bold text-dark mb-3">Open Admin Panel</h3>
                  <p class="text-secondary mb-0">Manage users, approve auction requests, and view system analytics.</p>
                </div>
              </a>
            </div>
          </div>
        </div>
      </main>
    </div>
  `,
  styles: [`
    .hover-translate-y:hover {
      transform: translateY(-5px);
      box-shadow: 0 1rem 3rem rgba(0,0,0,.1) !important;
    }
    .selection-card {
      transition: all 0.3s ease;
      cursor: pointer;
      background: white;
    }
    .text-primary {
      color: #4e73df !important; 
    }
    .bg-primary {
      background-color: #4e73df !important;
    }
    .border-primary {
      border-color: #4e73df !important;
    }
  `]
})
export class AdminSelect {
  private auth = inject(AuthService);
  private router = inject(Router);

  onLogout(): void {
    this.auth.logout();
    this.router.navigate(['/login']);
  }
}
