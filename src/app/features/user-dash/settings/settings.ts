import { Component, inject, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ReactiveFormsModule, FormGroup, FormControl, Validators, AbstractControl } from '@angular/forms';
import { DashboardService } from '../../../core/services/dashboard.service';

@Component({
  selector: 'app-settings',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule],
  templateUrl: './settings.html',
})
export class Settings {
  private dashboardService = inject(DashboardService);

  isLoading = signal(false);
  successMessage = signal<string | null>(null);
  errorMessage = signal<string | null>(null);

  passwordForm = new FormGroup(
    {
      current_password: new FormControl('', [Validators.required]),
      password: new FormControl('', [Validators.required, Validators.minLength(8)]),
      password_confirmation: new FormControl('', [Validators.required]),
    },
    { validators: this.passwordMatchValidator }
  );

  private passwordMatchValidator(g: AbstractControl) {
    return g.get('password')?.value === g.get('password_confirmation')?.value
      ? null : { passwordMismatch: true };
  }

  onChangePassword(): void {
    if (this.passwordForm.invalid) {
      this.passwordForm.markAllAsTouched();
      return;
    }

    this.isLoading.set(true);
    this.successMessage.set(null);
    this.errorMessage.set(null);

    const { current_password, password, password_confirmation } = this.passwordForm.value;

    this.dashboardService.updatePassword({
      current_password: current_password!,
      password: password!,
      password_confirmation: password_confirmation!,
    }).subscribe({
      next: (res) => {
        this.isLoading.set(false);
        this.successMessage.set(res.message || 'Password updated successfully.');
        this.passwordForm.reset();
      },
      error: (err: Error) => {
        this.isLoading.set(false);
        this.errorMessage.set(err.message);
      }
    });
  }
}
