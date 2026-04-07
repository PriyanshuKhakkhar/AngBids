import { Routes } from '@angular/router';
import { adminGuard } from './guards/admin.guard';

/**
 * Routes for the Admin Panel feature.
 * Everything nested here is protected by the adminGuard.
 */
export const ADMIN_ROUTES: Routes = [
  {
    path: '',
    canActivate: [adminGuard],
    loadComponent: () => import('./layout/admin-layout').then(m => m.AdminLayout),
    children: [
      { path: '', redirectTo: 'dashboard', pathMatch: 'full' },
      {
        path: 'dashboard',
        loadComponent: () => import('./pages/dashboard/dashboard').then(m => m.AdminDashboard),
      },
      {
        path: 'users',
        loadComponent: () => import('./pages/users/users').then(m => m.AdminUsers),
      },
      {
        path: 'auctions',
        loadComponent: () => import('./pages/auctions/auctions').then(m => m.AdminAuctions),
      },
      {
        path: 'activity',
        loadComponent: () => import('./pages/activity/activity').then(m => m.AdminActivity),
      }
    ],
  },
];
