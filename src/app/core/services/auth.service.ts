import { Injectable, inject, signal } from '@angular/core';
import { HttpClient, HttpErrorResponse } from '@angular/common/http';
import { Observable, throwError } from 'rxjs';
import { catchError, tap } from 'rxjs/operators';
import { User, AuthResponse, RegisterResponse } from '../models/user.model';
import { environment } from '../../../environments/environment';

export interface RegisterPayload {
  first_name: string;
  last_name: string;
  email: string;
  password: string;
  password_confirmation: string;
}

/**
 * AuthService - Handles Laravel API-based authentication
 *
 * Features:
 * - Login/Register with JWT token storage
 * - Persistent session via localStorage
 * - Token-based authentication for protected routes
 * - Safe JSON parsing with error handling
 */
@Injectable({
  providedIn: 'root'
})
export class AuthService {
  private http = inject(HttpClient);
  private apiUrl = environment.apiUrl;

  /** Reactive current user state — shared across the app */
  currentUser = signal<User | null>(null);

  constructor() {
    this.restoreSession();
  }

  /**
   * Authenticate user with email and password
   */
  login(email: string, password: string): Observable<AuthResponse> {
    // Ensure we start with a clean slate before logging in again
    this.clearSession();

    return this.http.post<AuthResponse>(`${this.apiUrl}/login`, { email, password }).pipe(
      tap(res => this.persistSession(res)),
      catchError(this.handleError)
    );
  }

  /**
   * Register a new user account.
   * NOTE: Session is NOT persisted here — user must verify OTP first.
   * The component stores the email and redirects to /verify-otp.
   */
  register(payload: RegisterPayload): Observable<RegisterResponse> {
    return this.http.post<RegisterResponse>(`${this.apiUrl}/register`, payload).pipe(
      catchError(this.handleError)
    );
  }

  /**
   * Verify OTP code sent to user's email.
   * If successful, Laravel now returns token + user for immediate login.
   */
  verifyOtp(email: string, otp: string): Observable<AuthResponse> {
    return this.http.post<AuthResponse>(`${this.apiUrl}/verify-otp?t=${Date.now()}`, { email, otp }).pipe(
      tap(res => {
        if (res.token && res.user) {
          this.persistSession(res);
          if (this.isSuperAdmin()) {
            console.log('[Login] Redirecting to Super Admin Dashboard');
          } else if (this.isAdmin()) {
            console.log('[Login] Redirecting to Admin Dashboard');
          } else {
            console.log('[Login] Redirecting to Home');
          }
        }
      }),
      catchError(this.handleError)
    );
  }

  /**
   * Request password reset link
   */
  forgotPassword(email: string): Observable<{ message: string }> {
    return this.http.post<{ message: string }>(`${this.apiUrl}/forgot-password`, { email }).pipe(
      catchError(this.handleError)
    );
  }

  /**
   * Reset password with token
   */
  resetPassword(payload: { email: string; token: string; password: string; password_confirmation: string }): Observable<{ message: string }> {
    return this.http.post<{ message: string }>(`${this.apiUrl}/reset-password`, payload).pipe(
      catchError(this.handleError)
    );
  }

  /**
   * Resend OTP code to user's email
   */
  resendOtp(email: string): Observable<{ message: string }> {
    return this.http.post<{ message: string }>(`${this.apiUrl}/resend-otp?t=${Date.now()}`, { email }).pipe(
      catchError(this.handleError)
    );
  }

  /**
   * Log out the current user.
   * Clears token and user data from localStorage and resets state.
   */
  logout(): void {
    this.clearSession();
  }

  /**
   * Centralized session cleanup — used by logout() and the HTTP interceptor
   * on 401/403 responses to avoid duplicated clearing logic.
   */
  clearSession(): void {
    this.currentUser.set(null);
    localStorage.removeItem('token');
    localStorage.removeItem('user');
  }

  /**
   * Check if the current user has admin privileges.
   * returns true for both 'admin' and 'super admin'
   */
  isAdmin(): boolean {
    return this.hasRole('admin') || this.hasRole('super admin') || this.hasRole('super_admin');
  }

  /**
   * Check if the current user is a Super Admin
   */
  isSuperAdmin(): boolean {
    return this.hasRole('super admin') || this.hasRole('super_admin');
  }

  /**
   * Check if the user has a specific role.
   * Supports:
   * 1. Backend roles array (Spatie standard)
   * 2. Singular role string property
   */
  hasRole(roleName: string): boolean {
    const user = this.currentUser();
    if (!user) return false;

    const target = roleName.toLowerCase();

    // 1. Check Spatie roles array (handles both strings and objects)
    if (user.roles?.length) {
      console.log('[AuthService] Checking roles:', user.roles);
      return user.roles.some((r: any) => {
        const name = typeof r === 'string' ? r.toLowerCase() : r.name?.toLowerCase();
        console.log(`[AuthService] Comparing role "${name}" with target "${target}"`);
        return name === target;
      });
    }

    // 2. Check singular role string
    if (user.role?.toLowerCase() === target) {
      return true;
    }

    // 3. Fallback for boolean is_admin flag (only if checking for 'admin')
    if (target === 'admin' && user.is_admin === true) {
      return true;
    }

    return false;
  }
  
  /**
   * Check if current user is KYC approved
   */
  isKycApproved(): boolean {
    const user = this.currentUser();
    if (!user) return false;
    
    // Admins are always approved
    if (this.isAdmin()) return true;
    
    return user.kyc_status === 'approved';
  }

  /**
   * Check if user is currently authenticated
   */
  isLoggedIn(): boolean {
    return !!this.getToken() && !!this.currentUser();
  }

  /**
   * Get the stored authentication token
   */
  getToken(): string | null {
    return localStorage.getItem('token');
  }

  /**
   * Get the current authenticated user
   */
  getCurrentUser(): User | null {
    return this.currentUser();
  }

  /**
   * Store authentication data in localStorage and update state
   */
  private persistSession(res: AuthResponse): void {
    if (res.token) {
      console.log('[AuthService] Storing token in localStorage');
      localStorage.setItem('token', res.token);
    }
    if (res.user) {
      console.log('[AuthService] Storing user data in localStorage:', res.user.email);
      const u = res.user;
      // Map name into firstName/lastName if missing — common in Laravel resources
      if (!u.firstName && !u.lastName && u.name) {
        const parts = u.name.split(' ');
        u.firstName = parts[0];
        u.lastName = parts.slice(1).join(' ');
      }
      localStorage.setItem('user', JSON.stringify(u));
      this.currentUser.set(u);
    }
  }

  /**
   * Restore user session from localStorage on app initialization
   * Safely parses stored user data with error handling
   */
  private restoreSession(): void {
    const token = localStorage.getItem('token');
    const storedUser = localStorage.getItem('user');

    if (token && storedUser) {
      try {
        const user = JSON.parse(storedUser);
        this.currentUser.set(user);
      } catch (error) {
        console.error('[AuthService] Failed to parse stored user data:', error);
        // Clear corrupted data
        localStorage.removeItem('user');
        localStorage.removeItem('token');
      }
    }
  }

  /**
   * Handle HTTP errors from authentication API
   */
  private handleError(error: HttpErrorResponse): Observable<never> {
    let errorMessage = 'An unexpected error occurred.';

    if (error.error instanceof ErrorEvent) {
      errorMessage = `Network Error: ${error.error.message}`;
    } else if (error.status === 0) {
      errorMessage = 'Backend is not reachable. Please check your internet connection or try again later.';
    } else if (error.status === 401) {
      errorMessage = error.error?.message || 'Unauthorized: Please log in to continue.';
    } else if (error.status === 403) {
      errorMessage = error.error?.message || 'Forbidden: You do not have permission to perform this action.';
    } else if (error.status === 404) {
      errorMessage = 'Not Found: The requested resource could not be found.';
    } else if (error.status === 422) {
      let validationErrors = '';
      if (error.error && error.error.errors) {
        const errors: any = error.error.errors;
        validationErrors = Object.keys(errors).map(key => errors[key]).join('\n');
      }
      errorMessage = validationErrors || error.error?.message || 'Validation failed. Please check your input.';
    } else if (error.status === 500) {
      errorMessage = 'Server Error: Something went wrong on our end. Please try again later.';
    } else {
      if (typeof error.error === 'string' && error.error.trim().startsWith('<')) {
        errorMessage = 'Invalid response from server. Please try again.';
      } else if (error.error instanceof SyntaxError || (error.error?.message && error.error.message.includes('JSON'))) {
        errorMessage = 'Invalid response format from server. Please try again.';
      } else {
        errorMessage = error.error?.message || error.message || `Unexpected Error: ${error.status}`;
      }
    }

    console.error('[AuthService Error]:', error);
    return throwError(() => new Error(errorMessage));
  }
}
