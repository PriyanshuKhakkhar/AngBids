import { Component, inject, signal } from '@angular/core';
import { RouterLink, Router } from '@angular/router';
import { AuthService } from '../../../core/services/auth.service';
import { CommonModule } from '@angular/common';
import { ReactiveFormsModule, FormGroup, FormControl, Validators } from '@angular/forms';

@Component({
  selector: 'app-login',
  templateUrl: './login.html',
  standalone: true,
  imports: [RouterLink, CommonModule, ReactiveFormsModule],
})
export class Login {
  private authService = inject(AuthService);
  private router = inject(Router);

  isLoading = signal(false);

  loginForm = new FormGroup({
    email: new FormControl('', [Validators.required, Validators.email]),
    password: new FormControl('', [Validators.required, Validators.minLength(6)])
  });

  onLogin(event: Event) {
    if (this.loginForm.invalid) return;
    
    this.isLoading.set(true);
    const { email, password } = this.loginForm.value;

    this.authService.login(email!, password!).subscribe({
      next: (res) => {
        this.isLoading.set(false);
        this.authService.currentUser.set(res.user);
        this.router.navigate(['/']);
      },
      error: () => this.isLoading.set(false)
    });
  }
}
