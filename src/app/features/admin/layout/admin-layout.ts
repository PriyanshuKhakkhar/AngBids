import { Component, inject } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterOutlet, RouterLink, RouterLinkActive, Router } from '@angular/router';
import { AuthService } from '../../../core/services/auth.service';

@Component({
  selector: 'app-admin-layout',
  standalone: true,
  imports: [CommonModule, RouterOutlet, RouterLink, RouterLinkActive],
  templateUrl: './admin-layout.html',
  styleUrl: './admin-layout.css'
})
export class AdminLayout {
  private auth = inject(AuthService);
  private router = inject(Router);

  currentDate = new Date();
  user = this.auth.currentUser();

  onLogout(): void {
    this.auth.logout();
    this.router.navigate(['/login']);
  }
}
