import { Component, inject, signal } from '@angular/core';
import { RouterLink } from '@angular/router';
import { CommonModule } from '@angular/common';
import { ReactiveFormsModule, FormGroup, FormControl, Validators } from '@angular/forms';
import { AuthService } from '../../../core/services/auth.service';

@Component({
  selector: 'app-forgot-password',
  templateUrl: './forgot-password.html',
  standalone: true,
  imports: [RouterLink, CommonModule, ReactiveFormsModule],
})
export class ForgotPassword {
  private authService = inject(AuthService);

  isLoading = signal(false);
  successMessage = signal<string | null>(null);
  errorMessage = signal<string | null>(null);

  form = new FormGroup({
    email: new FormControl('', [Validators.required, Validators.email]),
  });

  get email() { return this.form.get('email'); }

  onSubmit(): void {
    if (this.form.invalid) {
      this.form.markAllAsTouched();
      return;
    }

    this.isLoading.set(true);
    this.successMessage.set(null);
    this.errorMessage.set(null);

    console.log('[ForgotPassword] Sending reset link to:', this.form.value.email);

    this.authService.forgotPassword(this.form.value.email!).subscribe({
      next: (res) => {
        console.log('[ForgotPassword] Success:', res);
        this.isLoading.set(false);
        this.successMessage.set(res.message || 'Password reset link sent to your email.');
        this.form.reset();
      },
      error: (err: Error) => {
        console.error('[ForgotPassword] Error:', err);
        this.isLoading.set(false);
        this.errorMessage.set(err.message);
      }
    });
  }
}
