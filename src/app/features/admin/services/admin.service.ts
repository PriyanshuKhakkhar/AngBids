import { Injectable, inject } from '@angular/core';
import { HttpClient, HttpErrorResponse } from '@angular/common/http';
import { Observable, throwError, map, catchError } from 'rxjs';
import { environment } from '../../../../environments/environment';

// ─── Admin Dashboard Response Interfaces ─────────────────────────────────────

export interface AdminStats {
  total_users: number;
  new_users_this_month: number;
  total_auctions: number;
  active_auctions: number;
  pending_auctions: number;
  closed_auctions: number;
  cancelled_auctions: number;
  total_bids: number;
  bids_today: number;
  total_categories: number;
  unread_contacts: number;
}

export interface AdminRecentAuction {
  id: number;
  title: string;
  status: string;
  current_price: number;
  starting_price: number;
  end_time: string | null;
  start_time: string | null;
  category: { id: number; name: string } | null;
  user: { id: number; name: string } | null;
  bid_count: number;
}

export interface AdminRecentUser {
  id: number;
  name: string;
  email: string;
  roles?: string[];
  created_at: string;
}

export interface AdminDashboardData {
  stats: AdminStats;
  recent_auctions: AdminRecentAuction[];
  recent_users: AdminRecentUser[];
}

@Injectable({
  providedIn: 'root'
})
export class AdminService {
  private http = inject(HttpClient);
  private apiUrl = `${environment.apiUrl}/admin`;

  // ─── Dashboard ────────────────────────────────────────────────────────────

  /** Fetch full admin dashboard data from /api/admin/dashboard */
  getDashboard(): Observable<AdminDashboardData> {
    return this.http.get<{ status: boolean; data: AdminDashboardData }>(
      `${this.apiUrl}/dashboard`
    ).pipe(
      map(res => res.data),
      catchError(this.handleError)
    );
  }

  // Keep legacy methods for other admin pages
  getDashboardStats(): Observable<any> {
    return this.http.get<any>(`${this.apiUrl}/stats`).pipe(
      map(res => res.data || res)
    );
  }

  getRecentActivity(): Observable<any[]> {
    return this.http.get<any>(`${this.apiUrl}/activity`).pipe(
      map(res => res.data || res)
    );
  }

  // --- User Management ---
  getUsers(params?: any): Observable<any> {
    return this.http.get<any>(`${this.apiUrl}/users`, { params }).pipe(
      map(res => res.data || res)
    );
  }

  updateUser(id: number, data: any): Observable<any> {
    return this.http.put(`${this.apiUrl}/users/${id}`, data);
  }

  deleteUser(id: number): Observable<any> {
    return this.http.delete(`${this.apiUrl}/users/${id}`);
  }

  // --- Auction Control ---
  getAuctions(params?: any): Observable<any> {
    return this.http.get<any>(`${this.apiUrl}/auctions`, { params }).pipe(
      map(res => res.data || res)
    );
  }

  approveAuction(id: number): Observable<any> {
    return this.http.post(`${this.apiUrl}/auctions/${id}/approve`, {});
  }

  rejectAuction(id: number, reason: string): Observable<any> {
    return this.http.post(`${this.apiUrl}/auctions/${id}/reject`, { reason });
  }

  deleteAuction(id: number): Observable<any> {
    return this.http.delete(`${this.apiUrl}/auctions/${id}`);
  }

  createAuction(data: any): Observable<any> {
    return this.http.post(`${this.apiUrl}/auctions`, data);
  }

  getCategories(): Observable<any[]> {
    return this.http.get<any>(`${environment.apiUrl}/categories`).pipe(
      map(res => res.data || res)
    );
  }

  // ─── Error Handler ────────────────────────────────────────────────────────

  private handleError(error: HttpErrorResponse): Observable<never> {
    let message = 'An unexpected error occurred.';
    if (error.status === 0) message = 'Cannot reach the server. Check your connection.';
    else if (error.status === 401) message = 'Unauthorized: Please log in.';
    else if (error.status === 403) message = 'Forbidden: Admin access required.';
    else if (error.status === 404) message = 'Resource not found.';
    else if (error.status === 500) message = error.error?.message || 'Server error.';
    else message = error.error?.message || error.message || `Error ${error.status}`;
    console.error('[AdminService]', error);
    return throwError(() => new Error(message));
  }
}
