import { Routes } from '@angular/router';
import { Home } from './pages/home/home';
import { Auctions } from './pages/auctions/auctions';
import { AuctionDetails } from './pages/auction-details/auction-details';
import { Login } from './pages/login/login';
import { Register } from './pages/register/register';
import { VerifyOtp } from './pages/verify-otp/verify-otp';
import { About } from './pages/about/about';
import { Contact } from './pages/contact/contact';

export const routes: Routes = [
  { path: '', component: Home },
  { path: 'auctions', component: Auctions },
  { path: 'auction-details', component: AuctionDetails },
  { path: 'login', component: Login },
  { path: 'register', component: Register },
  { path: 'verify-otp', component: VerifyOtp },
  { path: 'about', component: About },
  { path: 'contact', component: Contact },
];
