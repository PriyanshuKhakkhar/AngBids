import { inject } from '@angular/core';
import { CanActivateFn, Router } from '@angular/router';
import { AuthService } from '../services/auth.service';

/**
 * AuthGuard - Prevents guests from accessing protected routes
 * Redirects to login and preserves the intended URL
 */
export const authGuard: CanActivateFn = (route, state) => {
  const auth = inject(AuthService);
  const router = inject(Router);

  if (auth.isLoggedIn()) {
    // Fallback to prevent admins from entering user dashboard
    if (state.url.startsWith('/dashboard') && auth.isAdmin()) {
      if (auth.isSuperAdmin()) {
        return router.createUrlTree(['/admin/super-dashboard']);
      }
      return router.createUrlTree(['/admin/dashboard']);
    }

    return true;
  }

  // Redirect to login with the return URL as a query parameter
  return router.createUrlTree(['/login'], {
    queryParams: { returnUrl: state.url }
  });
};
