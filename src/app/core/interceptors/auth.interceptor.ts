import { HttpInterceptorFn, HttpErrorResponse } from '@angular/common/http';
import { inject } from '@angular/core';
import { Router } from '@angular/router';
import { catchError, throwError } from 'rxjs';
import { AuthService } from '../services/auth.service';

/**
 * Public endpoints: token is NEVER attached, even if the user is logged in.
 * Use exact path segments to avoid false positives (e.g. /api/auctions/bids
 * is NOT the same as /api/auctions).
 */
const PUBLIC_PATHS = [
  '/api/login',
  '/api/register',
  '/api/forgot-password',
  '/api/reset-password',
  '/api/verify-otp',
  '/api/resend-otp',
  '/api/contact',
  '/api/home',
  '/api/categories',
];

/**
 * Public GET-only patterns: matched by both path prefix AND HTTP method.
 * /api/auctions  (list) and /api/auctions/:id (detail) are public reads.
 * Any POST/PUT/PATCH/DELETE to /api/auctions/* requires auth.
 */
const PUBLIC_GET_PREFIXES = [
  '/api/auctions',
];

function isPublicRequest(url: string, method: string): boolean {
  // Exact public path match (all methods)
  if (PUBLIC_PATHS.some(p => url.includes(p))) {
    return true;
  }
  // Public read-only prefix match (GET only)
  if (method === 'GET' && PUBLIC_GET_PREFIXES.some(p => url.includes(p))) {
    return true;
  }
  return false;
}

export const authInterceptor: HttpInterceptorFn = (req, next) => {
  const auth = inject(AuthService);
  const router = inject(Router);

  const token = auth.getToken();
  const isPublic = isPublicRequest(req.url, req.method);

  // Attach Bearer token only to protected requests
  if (token && !isPublic) {
    req = req.clone({
      setHeaders: { Authorization: `Bearer ${token}` }
    });
  }

  return next(req).pipe(
    catchError((error: HttpErrorResponse) => {
      // 401 Unauthorized or 403 Forbidden on a protected request:
      // clear session and redirect to login to avoid stale auth state.
      if ((error.status === 401 || error.status === 403) && !isPublic) {
        auth.clearSession();
        router.navigate(['/login'], {
          queryParams: { returnUrl: router.url }
        });
      }
      return throwError(() => error);
    })
  );
};
