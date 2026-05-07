import { Component, inject } from '@angular/core';
import { RouterLink, RouterLinkActive, Router } from '@angular/router';
import { AuthService } from '../../core/services/auth.service';
import { User } from '../../core/models/user.model';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-navbar',
  standalone: true,
  imports: [RouterLink, RouterLinkActive, CommonModule],
  templateUrl: './navbar.html',
  styleUrl: './navbar.css',
})
export class Navbar {
  protected authService = inject(AuthService);
  private router = inject(Router);

  /**
   * Returns the current authenticated user or null if not logged in.
   * Properly typed to support optional chaining in templates.
   */
  get currentUser(): User | null {
    return this.authService.currentUser();
  }

  get dashboardLink(): string {
    if (this.authService.isSuperAdmin()) return '/admin/super-dashboard';
    if (this.authService.isAdmin()) return '/admin/dashboard';
    return '/dashboard';
  }

  get profileLink(): string {
    if (this.authService.isSuperAdmin()) return '/admin/super-profile';
    if (this.authService.isAdmin()) return '/admin/profile';
    return '/dashboard/profile';
  }

  onGlobalSearch(event: Event): void {
    const el = event.target as HTMLInputElement;
    const value = el.value.trim();
    if (value) {
      this.router.navigate(['/auctions'], { queryParams: { q: value } });
      el.blur();
    }
  }

  logout(): void {
    this.authService.logout();
    this.router.navigate(['/']);
  }
}
