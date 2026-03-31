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
    ],
  },
];
