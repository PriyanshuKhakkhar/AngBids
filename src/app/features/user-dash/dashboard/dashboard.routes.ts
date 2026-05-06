import { Routes } from '@angular/router';

export const DASHBOARD_ROUTES: Routes = [
  {
    path: '',
    loadComponent: () => import('./dashboard').then(m => m.Dashboard),
    children: [
      {
        path: '',
        loadComponent: () => import('./overview').then(m => m.Overview),
      },
      {
        path: 'bids',
        loadComponent: () => import('../my-bids/my-bids').then(m => m.MyBids),
      },
      {
        path: 'watchlist',
        loadComponent: () => import('../watchlist/watchlist').then(m => m.Watchlist),
      },
      {
        path: 'profile',
        loadComponent: () => import('../profile/profile').then(m => m.Profile),
      },
      {
        path: 'settings',
        loadComponent: () => import('../settings/settings').then(m => m.Settings),
      },
      {
        path: 'verification',
        loadComponent: () => import('../verification/verification').then(m => m.Verification),
      },
      {
        path: 'my-listings',
        loadComponent: () => import('../my-listings/my-listings.component').then(m => m.MyListings),
      },
      {
        path: 'my-listings/create',
        loadComponent: () => import('../my-listings/create-auction.component').then(m => m.CreateAuction),
      },
    ],
  },
];
