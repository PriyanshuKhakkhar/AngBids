import { Component, OnInit, inject } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterOutlet, RouterLink, RouterLinkActive, Router } from '@angular/router';
import { AuthService } from '../../../core/services/auth.service';

@Component({
  selector: 'app-admin-layout',
  standalone: true,
  imports: [CommonModule, RouterOutlet, RouterLink, RouterLinkActive],
  template: `
    <div class="admin-wrapper d-flex min-vh-100 bg-light">
      <!-- Sidebar -->
      <aside class="admin-sidebar bg-dark text-white p-3 d-none d-md-flex flex-column" style="width: 260px;">
        <div class="sidebar-brand mb-4 py-2 border-bottom border-secondary text-center">
            <h4 class="m-0 text-gold fw-bold">AngBids <span class="text-white fs-6">Admin</span></h4>
        </div>
        
        <ul class="nav flex-column gap-2 mb-5">
            <li class="nav-item">
                <a class="nav-link text-white-50 rounded-3" routerLink="/admin/dashboard" routerLinkActive="active bg-primary text-white shadow-sm">
                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white-50 rounded-3" routerLink="/admin/auctions" routerLinkActive="active bg-primary text-white shadow-sm">
                    <i class="fas fa-gavel me-2"></i> Manage Auctions
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white-50 rounded-3" routerLink="/admin/users" routerLinkActive="active bg-primary text-white shadow-sm" [routerLinkActiveOptions]="{exact: true}">
                    <i class="fas fa-users me-2"></i> Manage Users
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white-50 rounded-3" href="javascript:void(0)">
                    <i class="fas fa-history me-2"></i> Manage Bids
                </a>
            </li>
        </ul>

        <div class="mt-auto p-3 bg-secondary bg-opacity-10 rounded-3 d-flex justify-content-between align-items-center">
            <div>
                <small class="text-white-50 d-block" style="font-size: 0.65rem;">ADMINISTRATOR</small>
                <div class="fw-bold small">{{ user()?.firstName }}</div>
            </div>
            <button (click)="onLogout()" class="btn btn-link text-danger p-0" title="Logout">
                <i class="fas fa-sign-out-alt"></i>
            </button>
        </div>
      </aside>

      <!-- Main Content -->
      <main class="flex-grow-1 d-flex flex-column overflow-hidden">
        <header class="bg-white border-bottom py-2 px-4 d-flex justify-content-between align-items-center shadow-sm" style="min-height: 60px;">
           <div class="small text-secondary fw-bold text-uppercase tracking-wider" style="font-size: 0.7rem;">
              Management Console
           </div>
           <div class="d-flex align-items-center gap-3">
              <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 small">System Online</span>
              <div class="text-dark small fw-bold">{{ currentDate | date:'mediumTime' }}</div>
           </div>
        </header>

        <div class="p-4 flex-grow-1 overflow-auto">
            <router-outlet></router-outlet>
        </div>
      </main>
    </div>
  `,
  styles: [`
    .admin-sidebar .nav-link:hover {
        background: rgba(255,255,255,0.05);
        color: #fff !important;
    }
    .admin-sidebar .nav-link.active {
        color: #fff !important;
        opacity: 1;
    }
    .text-gold {
        color: #d4af37;
    }
    .bg-light {
        background-color: #f8f9fc !important;
    }
  `]
})
export class AdminLayout implements OnInit {
  private auth = inject(AuthService);
  private router = inject(Router);

  currentDate = new Date();
  user = this.auth.currentUser;

  ngOnInit(): void {
    setInterval(() => {
      this.currentDate = new Date();
    }, 60000);
  }

  onLogout(): void {
    if (confirm('Are you sure you want to logout?')) {
      this.auth.logout();
      this.router.navigate(['/']);
    }
  }
}
