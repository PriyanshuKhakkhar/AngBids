import { Injectable, inject, signal } from '@angular/core';
import { HttpClient, HttpErrorResponse } from '@angular/common/http';
import { Observable, throwError } from 'rxjs';
import { catchError, tap } from 'rxjs/operators';
import { User, AuthResponse } from '../models/user.model';
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
   * @param email User email
   * @param password User password
   * @returns Observable with token and user data
   */
  login(email: string, password: string): Observable<AuthResponse> {
    return this.http.post<AuthResponse>(`${this.apiUrl}/login`, { email, password }).pipe(
      tap(res => this.persistSession(res)),
      catchError(this.handleError)
    );
  }

  /**
   * Register a new user account
   * @param payload Registration data
   * @returns Observable with token and user data
   */
  register(payload: RegisterPayload): Observable<AuthResponse> {
    return this.http.post<AuthResponse>(`${this.apiUrl}/register`, payload).pipe(
      tap(res => this.persistSession(res)),
      catchError(this.handleError)
    );
  }

  /**
   * Verify OTP code sent to user's email
   * @param email User email
   * @param otp OTP code
   * @returns Observable with success message
   */
  verifyOtp(email: string, otp: string): Observable<{ message: string }> {
    return this.http.post<{ message: string }>(`${this.apiUrl}/verify-otp`, { email, otp }).pipe(
      catchError(this.handleError)
    );
  }

  /**
   * Request password reset link
   * @param email User email
   * @returns Observable with success message
   */
  forgotPassword(email: string): Observable<{ message: string }> {
    return this.http.post<{ message: string }>(`${this.apiUrl}/forgot-password`, { email }).pipe(
      catchError(this.handleError)
    );
  }

  /**
   * Reset password with token
   * @param payload Reset password data
   * @returns Observable with success message
   */
  resetPassword(payload: { email: string; token: string; password: string; password_confirmation: string }): Observable<{ message: string }> {
    return this.http.post<{ message: string }>(`${this.apiUrl}/reset-password`, payload).pipe(
      catchError(this.handleError)
    );
  }

  /**
   * Resend OTP code to user's email
   * @param email User email
   * @returns Observable with success message
   */
  resendOtp(email: string): Observable<{ message: string }> {
    return this.http.post<{ message: string }>(`${this.apiUrl}/resend-otp`, { email }).pipe(
      catchError(this.handleError)
    );
  }

  /**
   * Log out the current user
   * Clears token and user data from localStorage and resets state
   */
  logout(): void {
    this.currentUser.set(null);
    localStorage.removeItem('token');
    localStorage.removeItem('user');
  }

  /**
   * Check if user is currently authenticated
   * @returns true if user is logged in, false otherwise
   */
  isLoggedIn(): boolean {
    return !!this.getToken() && !!this.currentUser();
  }

  /**
   * Get the stored authentication token
   * @returns JWT token or null if not authenticated
   */
  getToken(): string | null {
    return localStorage.getItem('token');
  }

  /**
   * Get the current authenticated user
   * @returns User object or null if not authenticated
   */
  getCurrentUser(): User | null {
    return this.currentUser();
  }

  /**
   * Store authentication data in localStorage and update state
   * @param res Authentication response from API
   */
  private persistSession(res: AuthResponse): void {
    if (res.token) {
      localStorage.setItem('token', res.token);
    }
    if (res.user) {
      localStorage.setItem('user', JSON.stringify(res.user));
      this.currentUser.set(res.user);
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
   * @param error HTTP error response
   * @returns Observable error with user-friendly message
   */
  private handleError(error: HttpErrorResponse): Observable<never> {
    let message = 'An unexpected error occurred.';
    
    if (error.error instanceof ErrorEvent) {
      // Client-side or network error
      message = `Network Error: ${error.error.message}`;
    } else {
      // Server-side error
      message = error.error?.message || error.message || `Server Error: ${error.status}`;
    }
    
    console.error('[AuthService Error]:', error);
    return throwError(() => new Error(message));
  }
}
