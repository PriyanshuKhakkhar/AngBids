import { Routes } from '@angular/router';
import { guestGuard } from '../../core/guards/guest.guard';

export const AUTH_ROUTES: Routes = [
  {
    path: 'login',
    canActivate: [guestGuard],
    loadComponent: () => import('./login/login').then(m => m.Login),
  },
  {
    path: 'register',
    canActivate: [guestGuard],
    loadComponent: () => import('./register/register').then(m => m.Register),
  },
  {
    path: 'verify-otp',
    // No guestGuard here — user has no session yet during OTP verification.
    // The component itself redirects to /register if email param is missing.
    loadComponent: () => import('./verify-otp/verify-otp').then(m => m.VerifyOtp),
  },
  {
    path: 'forgot-password',
    canActivate: [guestGuard],
    loadComponent: () => import('./forgot-password/forgot-password').then(m => m.ForgotPassword),
  },
  {
    path: 'reset-password',
    canActivate: [guestGuard],
    loadComponent: () => import('./reset-password/reset-password').then(m => m.ResetPassword),
  },
  {
    path: 'reset-password/:token',
    canActivate: [guestGuard],
    loadComponent: () => import('./reset-password/reset-password').then(m => m.ResetPassword),
  },
];
