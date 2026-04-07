import { Component, inject, signal, OnInit } from '@angular/core';
import { RouterLink, Router, ActivatedRoute } from '@angular/router';
import { CommonModule } from '@angular/common';
import { ReactiveFormsModule, FormControl, Validators } from '@angular/forms';
import { AuthService } from '../../../core/services/auth.service';

@Component({
  selector: 'app-verify-otp',
  templateUrl: './verify-otp.html',
  styleUrl: './verify-otp.css',
  standalone: true,
  imports: [RouterLink, CommonModule, ReactiveFormsModule],
})
export class VerifyOtp implements OnInit {
  private authService = inject(AuthService);
  private router = inject(Router);
  private route = inject(ActivatedRoute);

  isLoading = signal(false);
  isResending = signal(false);
  errorMessage = signal<string | null>(null);
  successMessage = signal<string | null>(null);

  /** Email passed as query param: /verify-otp?email=user@example.com */
  email = signal<string>('');

  otpControl = new FormControl('', [
    Validators.required,
    Validators.minLength(6),
    Validators.maxLength(6),
    Validators.pattern(/^\d{6}$/),
  ]);

  ngOnInit(): void {
    const emailParam = this.route.snapshot.queryParams['email'] ?? '';

    if (!emailParam) {
      // No email context — send the user back to register to restart the flow
      this.router.navigate(['/register']);
      return;
    }

    this.email.set(emailParam);
  }

  onVerify(): void {
    if (this.otpControl.invalid || this.isLoading()) return;

    this.otpControl.markAsTouched();
    this.isLoading.set(true);
    this.errorMessage.set(null);
    this.successMessage.set(null);

    this.authService.verifyOtp(this.email(), this.otpControl.value!).subscribe({
      next: (res) => {
        this.isLoading.set(false);
        this.successMessage.set(res.message || 'Account verified! Redirecting...');
        // Navigate to login so user can sign in with their verified account
        setTimeout(() => this.router.navigate(['/login']), 1500);
      },
      error: (err: Error) => {
        this.isLoading.set(false);
        this.errorMessage.set(err.message);
        // Clear OTP so user can re-enter without manually deleting
        this.otpControl.reset();
      }
    });
  }

  onResend(): void {
    if (!this.email() || this.isResending()) return;

    this.isResending.set(true);
    this.errorMessage.set(null);
    this.successMessage.set(null);

    this.authService.resendOtp(this.email()).subscribe({
      next: (res) => {
        this.isResending.set(false);
        this.successMessage.set(res.message || 'A new code has been sent to your email.');
      },
      error: (err: Error) => {
        this.isResending.set(false);
        this.errorMessage.set(err.message);
      }
    });
  }
}
