import { Component, OnInit, inject, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ReactiveFormsModule, FormGroup, FormControl, Validators } from '@angular/forms';
import { AuthService } from '../../../core/services/auth.service';
import { DashboardService } from '../../../core/services/dashboard.service';

@Component({
  selector: 'app-profile',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule],
  templateUrl: './profile.html',
})
export class Profile implements OnInit {
  private authService = inject(AuthService);
  private dashboardService = inject(DashboardService);

  isLoading = signal(false);
  successMessage = signal<string | null>(null);
  errorMessage = signal<string | null>(null);

  profileForm = new FormGroup({
    first_name: new FormControl('', [Validators.required, Validators.minLength(2)]),
    last_name: new FormControl('', [Validators.required, Validators.minLength(2)]),
    phone: new FormControl(''),
    address: new FormControl(''),
  });

  get user() { return this.authService.currentUser(); }

  ngOnInit(): void {
    const u = this.user;
    if (u) {
      // Split name if first_name/last_name exists or fallback to splitting 'name'
      let fName = u.firstName || '';
      let lName = u.lastName || '';
      // If we only have name, we split it for the UI form
      if (!fName && !lName && (u as any).name) {
          const parts = (u as any).name.split(' ');
          fName = parts[0];
          lName = parts.slice(1).join(' ');
      }

      this.profileForm.patchValue({
        first_name: fName,
        last_name: lName,
        phone: u.phone ?? '',
        address: u.address ?? (u as any).location ?? '',
      });
    }
  }

  onSave(): void {
    if (this.profileForm.invalid) {
      this.profileForm.markAllAsTouched();
      return;
    }

    this.isLoading.set(true);
    this.successMessage.set(null);
    this.errorMessage.set(null);

    const { first_name, last_name, phone, address } = this.profileForm.value;

    this.dashboardService.updateProfile({
      name: `${first_name} ${last_name}`.trim(),
      phone: phone ?? undefined,
      location: address ?? undefined,
    }).subscribe({
      next: (res) => {
        this.isLoading.set(false);
        this.successMessage.set(res.message || 'Profile updated successfully.');
        // Optionally fetch updated user and refresh auth.service.currentUser
      },
      error: (err: Error) => {
        this.isLoading.set(false);
        this.errorMessage.set(err.message);
      }
    });
  }
}
