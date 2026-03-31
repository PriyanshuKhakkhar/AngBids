import { HttpInterceptorFn } from '@angular/common/http';
import { inject } from '@angular/core';
import { AuthService } from '../services/auth.service';

/**
 * Auth Interceptor - Adds Bearer token to protected API requests
 * 
 * Public endpoints (no auth required):
 * - /api/auctions (GET)
 * - /api/auctions/{id} (GET)
 * - /api/categories
 * - /api/home
 * - /api/login
 * - /api/register
 * - /api/forgot-password
 * - /api/reset-password
 * - /api/verify-otp
 * - /api/resend-otp
 * - /api/contact
 * 
 * All other endpoints require authentication
 */
export const authInterceptor: HttpInterceptorFn = (req, next) => {
  const token = inject(AuthService).getToken();
  
  // List of public endpoints that don't require authentication
  const publicEndpoints = [
    '/api/auctions',
    '/api/categories',
    '/api/home',
    '/api/login',
    '/api/register',
    '/api/forgot-password',
    '/api/reset-password',
    '/api/verify-otp',
    '/api/resend-otp',
    '/api/contact'
  ];

  // Check if the request URL matches any public endpoint
  const isPublicEndpoint = publicEndpoints.some(endpoint => {
    // Handle both exact matches and parameterized routes (e.g., /api/auctions/123)
    if (endpoint === '/api/auctions') {
      return req.url.includes('/api/auctions') && req.method === 'GET';
    }
    return req.url.includes(endpoint);
  });

  // Only add token if it exists AND the endpoint is not public
  if (token && !isPublicEndpoint) {
    req = req.clone({
      setHeaders: { Authorization: `Bearer ${token}` }
    });
  }

  return next(req);
};
