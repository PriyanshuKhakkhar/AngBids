import { Injectable, inject } from '@angular/core';
import { HttpClient, HttpErrorResponse } from '@angular/common/http';
import { Observable, throwError } from 'rxjs';
import { catchError, retry } from 'rxjs/operators';
import { environment } from '../../../environments/environment';

export interface DashboardStats {
  active_bids: number;
  winning_bids: number;
  total_participated: number;
}

export interface RecentActivity {
  id: number;
  auction_title: string;
  bid_amount: number;
  status: 'winning' | 'outbid' | 'closed';
  created_at: string;
}

export interface DashboardData {
  stats: DashboardStats;
  recent_activity: RecentActivity[];
}

export interface UserBid {
  id: number;
  auction_id: number;
  auction_title: string;
  auction_image: string;
  amount: number;
  status: 'winning' | 'outbid' | 'closed';
  end_date: string;
  created_at: string;
}

export interface ProfileUpdatePayload {
  first_name: string;
  last_name: string;
  phone?: string;
  address?: string;
}

export interface PasswordUpdatePayload {
  current_password: string;
  password: string;
  password_confirmation: string;
}

@Injectable({
  providedIn: 'root'
})
export class DashboardService {
  private http = inject(HttpClient);
  private baseUrl = `${environment.apiUrl}/user`;

  getDashboardData(): Observable<DashboardData> {
    return this.http.get<DashboardData>(`${this.baseUrl}/dashboard`).pipe(
      retry(1),
      catchError(this.handleError)
    );
  }

  getMyBids(): Observable<UserBid[]> {
    return this.http.get<UserBid[]>(`${this.baseUrl}/bids`).pipe(
      retry(1),
      catchError(this.handleError)
    );
  }

  updateProfile(payload: ProfileUpdatePayload): Observable<{ message: string }> {
    return this.http.put<{ message: string }>(`${this.baseUrl}/profile`, payload).pipe(
      catchError(this.handleError)
    );
  }

  updatePassword(payload: PasswordUpdatePayload): Observable<{ message: string }> {
    return this.http.put<{ message: string }>(`${this.baseUrl}/password`, payload).pipe(
      catchError(this.handleError)
    );
  }

  private handleError(error: HttpErrorResponse) {
    const message = error.error?.message || error.message || 'Failed to load data.';
    console.error('[DashboardService Error]:', error);
    return throwError(() => new Error(message));
  }
}
