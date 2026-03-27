import { Injectable, signal } from '@angular/core';
import { User, AuthResponse } from '../models/user.model';
import { Observable, of } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  // Using Signals for modern state management (Standard in Angular 17+)
  currentUser = signal<User | null>(null);
  
  constructor() {}

  /**
   * Stub for Login call to Laravel API later.
   */
  login(email: string, password: string): Observable<AuthResponse> {
    console.log('Login attempt for:', email);
    // This will eventually be this.http.post<AuthResponse>(...)
    return of({
      token: 'fake-jwt-token',
      user: {
        id: 1,
        firstName: 'Guest',
        lastName: 'User',
        email: email,
        role: 'user',
        isVerified: true
      }
    });
  }

  /**
   * Check if user is logged in.
   */
  isLoggedIn(): boolean {
    return !!this.currentUser();
  }

  /**
   * Logout and clear session.
   */
  logout() {
    this.currentUser.set(null);
    localStorage.removeItem('token');
  }
}
