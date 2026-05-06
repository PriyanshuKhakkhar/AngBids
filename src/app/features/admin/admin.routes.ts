import { Routes } from '@angular/router';
import { adminGuard } from '../../core/guards/admin.guard';
import { superAdminGuard } from '../../core/guards/super-admin.guard';

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
        path: 'super-dashboard', 
        canActivate: [superAdminGuard],
        loadComponent: () => import('./pages/dashboard/dashboard').then(m => m.SuperAdminDashboard) 
      },
      { 
        path: 'auction-approvals', 
        loadComponent: () => import('./pages/auction-approvals/auction-approvals.component').then(m => m.AuctionApprovalsComponent) 
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
        path: 'kyc', 
        loadComponent: () => import('./pages/kyc-management/kyc-management').then(m => m.KycManagementComponent) 
      },
      { 
        path: 'bids', 
        redirectTo: 'activity',
        pathMatch: 'full' 
      }
    ]
  }
];
