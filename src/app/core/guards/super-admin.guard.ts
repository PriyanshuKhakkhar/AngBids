import { inject } from '@angular/core';
import { CanActivateFn, Router } from '@angular/router';
import { AuthService } from '../services/auth.service';

/**
 * SuperAdminGuard - Restricts access to super-administrative routes.
 * Only users with the 'super admin' role are allowed to pass.
 */
export const superAdminGuard: CanActivateFn = (route, state) => {
  const auth = inject(AuthService);
  const router = inject(Router);

  // 1. Check if the user is even logged in
  if (!auth.isLoggedIn()) {
    console.warn('[SuperAdminGuard] Access denied: User not authenticated.');
    return router.createUrlTree(['/login'], {
      queryParams: { returnUrl: state.url }
    });
  }

  // 2. Check if the user has super admin privileges
  if (auth.isSuperAdmin()) {
    return true;
  }

  // 3. Logged in but not a super admin -> Redirect to Admin Dashboard
  console.warn('[SuperAdminGuard] Access denied: Insufficient permissions (Super Admin only).');
  return router.createUrlTree(['/admin/dashboard']);
};
