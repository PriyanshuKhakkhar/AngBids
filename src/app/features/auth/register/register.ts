import { Component, inject, signal } from '@angular/core';
import { RouterLink, Router } from '@angular/router';
import { CommonModule } from '@angular/common';
import { ReactiveFormsModule, FormGroup, FormControl, Validators, AbstractControl } from '@angular/forms';
import { AuthService } from '../../../core/services/auth.service';

@Component({
  selector: 'app-register',
  templateUrl: './register.html',
  styleUrl: './register.css',
  standalone: true,
  imports: [RouterLink, CommonModule, ReactiveFormsModule],
})
export class Register {
  private authService = inject(AuthService);
  private router = inject(Router);

  isLoading = signal(false);
  errorMessage = signal<string | null>(null);
  successMessage = signal<string | null>(null);
  showPassword = signal(false);
  showConfirmPassword = signal(false);

  registerForm = new FormGroup(
    {
      first_name: new FormControl('', [Validators.required, Validators.minLength(2)]),
      last_name: new FormControl('', [Validators.required, Validators.minLength(2)]),
      email: new FormControl('', [Validators.required, Validators.email]),
      password: new FormControl('', [Validators.required, Validators.minLength(8)]),
      password_confirmation: new FormControl('', [Validators.required]),
      terms: new FormControl(false, [Validators.requiredTrue]),
    },
    { validators: this.passwordMatchValidator }
  );

  togglePassword(): void {
    this.showPassword.set(!this.showPassword());
  }

  toggleConfirmPassword(): void {
    this.showConfirmPassword.set(!this.showConfirmPassword());
  }

  private passwordMatchValidator(group: AbstractControl) {
    const pw = group.get('password')?.value;
    const confirm = group.get('password_confirmation')?.value;
    return pw === confirm ? null : { passwordMismatch: true };
  }

  onRegister(): void {
    if (this.registerForm.invalid) {
      this.registerForm.markAllAsTouched();
      return;
    }

    this.isLoading.set(true);
    this.errorMessage.set(null);

    const { first_name, last_name, email, password, password_confirmation } = this.registerForm.value;

    this.authService.register({
      first_name: first_name!,
      last_name: last_name!,
      email: email!,
      password: password!,
      password_confirmation: password_confirmation!,
    }).subscribe({
      next: (res) => {
        this.isLoading.set(false);
        this.successMessage.set('Account created! Redirecting to verification...');
        
        // Persist email in localStorage as a fallback for verify-otp page
        localStorage.setItem('pending_email', email!);
        
        // Pass the registered email to the verify-otp route
        setTimeout(() => {
          this.router.navigate(['/verify-otp'], { queryParams: { email: email! } });
        }, 1000);
      },
      error: (err: Error) => {
        this.isLoading.set(false);
        this.errorMessage.set(err.message);
      }
    });
  }
}
