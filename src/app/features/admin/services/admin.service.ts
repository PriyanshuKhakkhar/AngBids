import { Injectable, inject } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, catchError, throwError } from 'rxjs';
import { environment } from '../../../../environments/environment';

@Injectable({
  providedIn: 'root'
})
export class AdminService {
  private http = inject(HttpClient);
  private apiUrl = `${environment.apiUrl}/admin`;

  /**
   * Get overall system statistics for admin dashboard
   */
  getStats(): Observable<any> {
    return this.http.get(`${this.apiUrl}/dashboard`).pipe(
      catchError(this.handleError)
    );
  }

  /**
   * Get paginated list of users for management
   */
  getUsers(params?: any): Observable<any> {
    return this.http.get(`${this.apiUrl}/users`, { params }).pipe(
      catchError(this.handleError)
    );
  }

  /**
   * Get list of auctions for moderation
   */
  getAuctions(params?: any): Observable<any> {
    return this.http.get(`${this.apiUrl}/auctions`, { params }).pipe(
      catchError(this.handleError)
    );
  }

  private handleError(error: any) {
    console.error('[AdminService Error]:', error);
    return throwError(() => new Error(error.error?.message || 'An error occurred during admin operation.'));
  }
}
