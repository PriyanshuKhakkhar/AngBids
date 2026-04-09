import { inject } from '@angular/core';
import { CanActivateFn, Router } from '@angular/router';
import { AuthService } from '../services/auth.service';

/**
 * AdminGuard - Restricts access to administrative routes.
 * Only users with the 'admin' role are allowed to pass.
 */
export const adminGuard: CanActivateFn = (route, state) => {
  const auth = inject(AuthService);
  const router = inject(Router);

  // 1. Check if the user is even logged in
  if (!auth.isLoggedIn()) {
    console.warn('[AdminGuard] Access denied: User not authenticated.');
    return router.createUrlTree(['/login'], {
      queryParams: { returnUrl: state.url }
    });
  }

  // 2. Check if the user has admin privileges
  if (auth.isAdmin()) {
    return true;
  }

  // 3. Logged in but not an admin -> Redirect to Home
  console.warn('[AdminGuard] Access denied: Insufficient permissions (Admin only).');
  return router.createUrlTree(['/']);
};
