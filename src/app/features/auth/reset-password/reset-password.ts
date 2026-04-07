import { Component, inject, signal, OnInit } from '@angular/core';
import { RouterLink, Router, ActivatedRoute } from '@angular/router';
import { CommonModule } from '@angular/common';
import { ReactiveFormsModule, FormGroup, FormControl, Validators, AbstractControl } from '@angular/forms';
import { AuthService } from '../../../core/services/auth.service';

@Component({
  selector: 'app-reset-password',
  templateUrl: './reset-password.html',
  standalone: true,
  imports: [RouterLink, CommonModule, ReactiveFormsModule],
})
export class ResetPassword implements OnInit {
  private authService = inject(AuthService);
  private router = inject(Router);
  private route = inject(ActivatedRoute);

  isLoading = signal(false);
  successMessage = signal<string | null>(null);
  errorMessage = signal<string | null>(null);
  email = signal('');
  token = signal('');

  form = new FormGroup(
    {
      password: new FormControl('', [Validators.required, Validators.minLength(8)]),
      password_confirmation: new FormControl('', [Validators.required]),
    },
    { validators: this.passwordMatchValidator }
  );

  private passwordMatchValidator(g: AbstractControl) {
    return g.get('password')?.value === g.get('password_confirmation')?.value
      ? null : { passwordMismatch: true };
  }

  ngOnInit(): void {
    // Capture from both query params and route params to be safe
    const queryEmail = this.route.snapshot.queryParams['email'];
    const queryToken = this.route.snapshot.queryParams['token'];
    const pathToken = this.route.snapshot.params['token'];

    this.email.set(queryEmail ?? '');
    this.token.set(queryToken ?? pathToken ?? '');
    
    // Explicitly show an error if token is completely missing
    if (!this.token()) {
      this.errorMessage.set('Invalid or missing reset token. Please request a new password reset link.');
    }
  }

  onSubmit(): void {
    if (this.form.invalid) {
      this.form.markAllAsTouched();
      return;
    }

    this.isLoading.set(true);
    this.errorMessage.set(null);

    const { password, password_confirmation } = this.form.value;

    this.authService.resetPassword({
      email: this.email(),
      token: this.token(),
      password: password!,
      password_confirmation: password_confirmation!,
    }).subscribe({
      next: (res) => {
        this.isLoading.set(false);
        this.successMessage.set(res.message || 'Password reset! Redirecting to login...');
        setTimeout(() => this.router.navigate(['/login']), 2000);
      },
      error: (err: Error) => {
        this.isLoading.set(false);
        this.errorMessage.set(err.message);
      }
    });
  }
}
