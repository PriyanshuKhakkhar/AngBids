import { Routes } from '@angular/router';

export const AUCTIONS_ROUTES: Routes = [
  {
    path: '',
    loadComponent: () => import('./auctions/auctions').then(m => m.Auctions),
  },
  {
    path: ':id',
    loadComponent: () => import('./auction-details/auction-details').then(m => m.AuctionDetails),
  },
];
