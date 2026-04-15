import { Routes } from '@angular/router';
import { adminGuard } from '../../core/guards/admin.guard';

export const ADMIN_ROUTES: Routes = [
  {
    path: '',
    canActivate: [adminGuard],
    loadComponent: () => import('./layout/admin-layout').then(m => m.AdminLayout),
    children: [
      { path: '', redirectTo: 'dashboard', pathMatch: 'full' },
      { 
        path: 'dashboard', 
        loadComponent: () => import('./pages/admin-dashboard/admin-dashboard.component').then(m => m.AdminDashboardComponent) 
      },
      { 
        path: 'auctions', 
        loadComponent: () => import('./pages/manage-auctions/manage-auctions.component').then(m => m.ManageAuctionsComponent) 
      },
      { 
        path: 'users', 
        loadComponent: () => import('./pages/manage-users/manage-users.component').then(m => m.ManageUsersComponent) 
      },
      { 
        path: 'activity', 
        loadComponent: () => import('./pages/activity/activity').then(m => m.AdminActivity) 
      },
      { 
        path: 'bids', 
        redirectTo: 'activity',
        pathMatch: 'full' 
      }
    ]
  }
];
