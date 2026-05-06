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
    // Use observable to handle query params reliably
    this.route.queryParams.subscribe(params => {
      let emailParam = params['email'] || '';
      
      // Fallback to localStorage if query param is missing (e.g. on refresh)
      if (!emailParam) {
        emailParam = localStorage.getItem('pending_email') || '';
        console.log('[VerifyOtp] Query param missing, falling back to localStorage:', emailParam);
      } else {
        // Update localStorage with current param to keep them in sync
        localStorage.setItem('pending_email', emailParam);
      }

      console.log('[VerifyOtp] Final email context:', emailParam);
      
      if (!emailParam) {
        console.error('[VerifyOtp] No email context found anywhere. Redirecting to /register...');
        this.errorMessage.set('Session lost. Redirecting to registration...');
        setTimeout(() => this.router.navigate(['/register']), 2000);
      } else {
        this.email.set(emailParam);
      }
    });
  }

  onVerify(event?: Event): void {
    if (event) {
      event.preventDefault();
      event.stopPropagation();
    }

    if (this.otpControl.invalid || this.isLoading()) {
      console.warn('[VerifyOtp] Cannot verify: Form invalid or already loading', {
        value: this.otpControl.value,
        errors: this.otpControl.errors
      });
      this.otpControl.markAsTouched();
      return;
    }

    const otp = this.otpControl.value?.trim();
    if (!otp || otp.length !== 6) {
      this.errorMessage.set('Please enter a valid 6-digit code.');
      return;
    }

    this.isLoading.set(true);
    this.errorMessage.set(null);
    this.successMessage.set(null);

    console.log(`[VerifyOtp] OTP submitted for: ${this.email()} - Code: ${otp}`);

    this.authService.verifyOtp(this.email(), otp).subscribe({
      next: (res) => {
        console.log('[VerifyOtp] Verification SUCCESS:', {
          message: res.message,
          hasToken: !!res.token,
          user: res.user?.email
        });
        
        this.isLoading.set(false);
        this.successMessage.set('Verification successful! Redirecting to dashboard...');
        
        // Ensure the success message is seen before navigating
        setTimeout(() => {
          console.log('[VerifyOtp] Storing session and navigating to home...');
          localStorage.removeItem('pending_email');
          
          // Redirect to home/dashboard since we are now logged in
          this.router.navigate(['/']); 
        }, 1500);
      },
      error: (err: Error) => {
        console.error('[VerifyOtp] Verification FAILED:', err);
        this.isLoading.set(false);
        this.errorMessage.set(err.message || 'Verification failed. Please check the code and try again.');
        
        // If it's a 404/422 indicating user doesn't exist, we might want to redirect
        if (err.message.includes('not found') || err.message.includes('doesn\'t exist')) {
           console.warn('[VerifyOtp] User not found, redirecting back to register');
           setTimeout(() => this.router.navigate(['/register']), 3000);
        }
      }
    });
  }

  onResend(): void {
    if (!this.email() || this.isResending()) return;

    this.isResending.set(true);
    this.errorMessage.set(null);
    this.successMessage.set(null);

    console.log(`[VerifyOtp] Resending OTP to: ${this.email()}`);

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
