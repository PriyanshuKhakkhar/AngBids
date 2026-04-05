import { inject } from '@angular/core';
import { CanActivateFn, Router } from '@angular/router';
import { AuthService } from '../services/auth.service';

/**
 * GuestGuard - Prevents authenticated users from visiting guest-only pages
 * such as login, register, forgot-password, reset-password, and verify-otp.
 */
export const guestGuard: CanActivateFn = () => {
  const auth = inject(AuthService);
  const router = inject(Router);

  if (!auth.isLoggedIn()) {
    return true;
  }

  // Redirect authenticated users to the dashboard
  return router.createUrlTree(['/dashboard']);
};
