import { Component, inject } from '@angular/core';
import { RouterOutlet, RouterLink, RouterLinkActive, Router } from '@angular/router';
import { CommonModule } from '@angular/common';
import { AuthService } from '../../../core/services/auth.service';

@Component({
  selector: 'app-dashboard',
  standalone: true,
  imports: [RouterOutlet, RouterLink, RouterLinkActive, CommonModule],
  templateUrl: './dashboard.html',
  styleUrl: './dashboard.css'
})
export class Dashboard {
  public authService = inject(AuthService);
  private router = inject(Router);

  menuItems = [
    { label: 'Overview',          icon: 'fas fa-th-large',    link: '/dashboard',           exact: true  },
    { label: 'My Bids',           icon: 'fas fa-gavel',       link: '/dashboard/bids',      exact: false },
    { label: 'Watchlist',         icon: 'fas fa-heart',       link: '/dashboard/watchlist', exact: false },
    { label: 'Profile',           icon: 'fas fa-user-edit',   link: '/dashboard/profile',   exact: false },
    { label: 'Settings',          icon: 'fas fa-cog',         link: '/dashboard/settings',  exact: false },
  ];

  get user() { return this.authService.currentUser(); }

  get avatarUrl(): string {
    const u = this.user;
    return u
      ? `https://ui-avatars.com/api/?name=${u.firstName}+${u.lastName}&background=D4AF37&color=fff`
      : 'https://ui-avatars.com/api/?name=Guest+User&background=D4AF37&color=fff';
  }

  logout(): void {
    this.authService.logout();
    this.router.navigate(['/login']);
  }
}
