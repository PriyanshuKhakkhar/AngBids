import { Routes } from '@angular/router';
import { Home } from './features/public/home/home';
import { About } from './features/public/about/about';
import { Contact } from './features/public/contact/contact';
import { Auctions } from './features/auctions/auctions/auctions';
import { AuctionDetails } from './features/auctions/auction-details/auction-details';
import { Login } from './features/auth/login/login';
import { Register } from './features/auth/register/register';
import { VerifyOtp } from './features/auth/verify-otp/verify-otp';
import { Dashboard } from './features/user-dash/dashboard/dashboard';
import { Overview } from './features/user-dash/dashboard/overview';

export const routes: Routes = [
  { path: '', component: Home },
  { path: 'auctions', component: Auctions },
  { path: 'auction-details', component: AuctionDetails },
  { path: 'login', component: Login },
  { path: 'register', component: Register },
  { path: 'verify-otp', component: VerifyOtp },
  {
    path: 'dashboard',
    component: Dashboard,
    children: [
      { path: '', component: Overview },
      // Later: { path: 'bids', component: MyBidsComponent }
    ]
  },
  { path: 'about', component: About },
  { path: 'contact', component: Contact },
];
