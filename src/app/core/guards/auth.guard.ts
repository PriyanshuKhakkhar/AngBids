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
    return true;
  }

  // Redirect to login with the return URL as a query parameter
  return router.createUrlTree(['/login'], {
    queryParams: { returnUrl: state.url }
  });
};
