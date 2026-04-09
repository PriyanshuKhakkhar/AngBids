import { Injectable, inject } from '@angular/core';
import { HttpClient, HttpErrorResponse } from '@angular/common/http';
import { Observable, throwError, forkJoin } from 'rxjs';
import { catchError, retry, map } from 'rxjs/operators';
import { environment } from '../../../environments/environment';
import { Auction } from '../models/auction.model';

export interface DashboardStats {
  active_bids: number;
  winning_bids: number;
  total_participated: number;
}

export interface RecentActivity {
  id: number;
  auction_id?: number;
  auction_title: string;
  auction_image?: string;
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
  name: string;
  phone?: string;
  location?: string;
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
    return forkJoin({
      statsRes: this.http.get<{success: boolean, data: any}>(`${this.baseUrl}/stats`),
      bidsRes: this.http.get<{success: boolean, data: any[]}>(`${this.baseUrl}/bids?per_page=5`)
    }).pipe(
      map(({ statsRes, bidsRes }) => {
        const statsData = statsRes.data;
        return {
          stats: {
            active_bids: statsData.active_auctions || 0,
            winning_bids: statsData.items_won || 0,
            total_participated: statsData.total_bids || 0
          },
          recent_activity: bidsRes.data.map((bid: any) => ({
            id: bid.id,
            auction_id: bid.auction?.id,
            auction_title: bid.auction?.title || 'Untitled Auction',
            auction_image: bid.auction?.image_url ? 
              (bid.auction.image_url.includes('http') ? bid.auction.image_url : `/storage/${bid.auction.image_url}`) : 
              'https://placehold.co/400x400/4e73df/white?text=Auction+Item',
            bid_amount: bid.amount,
            status: (bid.auction?.status === 'closed' || bid.auction?.status === 'ended' ? 'closed' : (bid.is_winning ? 'winning' : 'outbid')) as 'winning' | 'outbid' | 'closed',
            created_at: bid.placed_at
          }))
        };
      }),
      retry(1),
      catchError(this.handleError)
    );
  }

  getMyBids(): Observable<UserBid[]> {
    return this.http.get<{data: any[]}>(`${this.baseUrl}/bids`).pipe(
      map(res => res.data.map(bid => ({
        id: bid.id,
        auction_id: bid.auction?.id,
        auction_title: bid.auction?.title,
        auction_image: bid.auction?.image_url,
        amount: bid.amount,
        status: (bid.auction?.status === 'closed' || bid.auction?.status === 'ended' ? 'closed' : (bid.is_winning ? 'winning' : 'outbid')) as 'winning' | 'outbid' | 'closed',
        end_date: bid.auction?.end_time,
        created_at: bid.placed_at
      }))),
      retry(1),
      catchError(this.handleError)
    );
  }

  getWatchlist(): Observable<Auction[]> {
    return this.http.get<{data: Auction[]}>(`${environment.apiUrl}/watchlist`).pipe(
      map(res => res.data),
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

  submitKyc(formData: FormData): Observable<{ message: string }> {
    return this.http.post<{ message: string }>(`${this.baseUrl}/kyc`, formData).pipe(
      catchError(this.handleError)
    );
  }

  getKycStatus(): Observable<any> {
    return this.http.get<any>(`${this.baseUrl}/kyc`).pipe(
      catchError(this.handleError)
    );
  }

  private handleError(error: HttpErrorResponse): Observable<never> {
    let errorMessage = 'An unexpected error occurred.';

    if (error.error instanceof ErrorEvent) {
      errorMessage = `Network Error: ${error.error.message}`;
    } else if (error.status === 0) {
      errorMessage = 'Backend is not reachable. Please check your internet connection or try again later.';
    } else if (error.status === 401) {
      errorMessage = 'Unauthorized: Please log in to continue.';
    } else if (error.status === 403) {
      errorMessage = 'Forbidden: You do not have permission to perform this action.';
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

    console.error('[DashboardService Error]:', error);
    return throwError(() => new Error(errorMessage));
  }
}
