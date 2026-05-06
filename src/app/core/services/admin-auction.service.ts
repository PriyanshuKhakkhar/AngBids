import { Injectable, inject } from '@angular/core';
import { HttpClient, HttpErrorResponse, HttpParams } from '@angular/common/http';
import { Observable, throwError } from 'rxjs';
import { catchError, map } from 'rxjs/operators';
import { environment } from '../../../environments/environment';

/**
 * Admin-facing auction model — includes all fields needed for the approval panel.
 */
export interface AdminAuction {
  id: number;
  title: string;
  description: string;
  sellerName: string;
  sellerEmail?: string;
  category: string;
  startingPrice: number;
  currentBid: number;
  status: 'pending' | 'active' | 'closed' | 'cancelled';
  startTime: string;
  endTime: string;
  submittedAt: string;
  imageUrl: string;
  totalBids: number;
}

/**
 * AdminAuctionService
 *
 * Handles all admin-side auction management operations:
 * - Listing all auctions (with optional status filter) → GET /api/admin/auctions
 * - Approving a pending auction → POST /api/admin/auctions/{id}/approve
 * - Rejecting (cancelling) a pending auction → POST /api/admin/auctions/{id}/cancel
 */
@Injectable({
  providedIn: 'root'
})
export class AdminAuctionService {
  private http = inject(HttpClient);
  private apiUrl = `${environment.apiUrl}/admin/auctions`;
  private baseUrl = environment.apiUrl.replace('/api', '');

  /**
   * Fetch auctions with optional filters (status, search, page).
   * Admin can see all statuses including pending.
   */
  getAuctions(params?: { status?: string; search?: string; page?: number }): Observable<{
    data: AdminAuction[];
    current_page: number;
    last_page: number;
    total: number;
  }> {
    let httpParams = new HttpParams();
    if (params?.status && params.status !== 'all') {
      httpParams = httpParams.set('status', params.status);
    }
    if (params?.search) {
      httpParams = httpParams.set('search', params.search);
    }
    if (params?.page) {
      httpParams = httpParams.set('page', params.page.toString());
    }

    return this.http.get<any>(this.apiUrl, { params: httpParams }).pipe(
      map(response => {
        const rawData = response.data || [];
        return {
          data: Array.isArray(rawData) ? rawData.map((item: any) => this.mapToAdminAuction(item)) : [],
          current_page: response.meta?.current_page || 1,
          last_page: response.meta?.last_page || 1,
          total: response.meta?.total || 0
        };
      }),
      catchError(this.handleError)
    );
  }

  /**
   * Approve a pending auction (sets status to 'active').
   * Endpoint: POST /api/admin/auctions/{id}/approve
   */
  approveAuction(id: number): Observable<{ message: string }> {
    return this.http.post<any>(`${this.apiUrl}/${id}/approve`, {}).pipe(
      map(response => ({ message: response.message || 'Auction approved successfully.' })),
      catchError(this.handleError)
    );
  }

  /**
   * Reject a pending auction (sets status to 'cancelled').
   * Endpoint: POST /api/admin/auctions/{id}/cancel
   */
  rejectAuction(id: number, reason: string): Observable<{ message: string }> {
    return this.http.post<any>(`${this.apiUrl}/${id}/cancel`, { reason }).pipe(
      map(response => ({ message: response.message || 'Auction rejected.' })),
      catchError(this.handleError)
    );
  }

  /**
   * Maps raw API response to the AdminAuction frontend model.
   */
  private mapToAdminAuction(raw: any): AdminAuction {
    const placeholder = 'https://placehold.co/400x300/1a1a2e/white?text=No+Image';
    let imageUrl = placeholder;
    const firstImage = raw.images?.[0]?.url || raw.image;

    if (firstImage && typeof firstImage === 'string') {
      const lastHttp = firstImage.lastIndexOf('http');
      if (lastHttp > 0) {
        imageUrl = firstImage.substring(lastHttp);
      } else if (firstImage.startsWith('http')) {
        imageUrl = firstImage;
      } else {
        imageUrl = `${this.baseUrl}/storage/${firstImage.replace(/^\//, '')}`;
      }
    }

    return {
      id: raw.id,
      title: raw.title || 'Untitled',
      description: raw.description || '',
      sellerName: raw.user?.name || 'Unknown Seller',
      sellerEmail: raw.user?.email,
      category: raw.category?.name ?? 'Uncategorized',
      startingPrice: parseFloat(raw.starting_price ?? 0),
      currentBid: parseFloat(raw.current_price ?? raw.starting_price ?? 0),
      status: raw.status ?? 'pending',
      startTime: raw.start_time ?? '',
      endTime: raw.end_time ?? '',
      submittedAt: raw.created_at ?? '',
      imageUrl,
      totalBids: raw.bids_count ?? 0
    };
  }

  private handleError(error: HttpErrorResponse): Observable<never> {
    let errorMessage = 'An unexpected error occurred.';

    if (error.error instanceof ErrorEvent) {
      errorMessage = `Network Error: ${error.error.message}`;
    } else if (error.status === 0) {
      errorMessage = 'Backend is not reachable.';
    } else if (error.status === 401) {
      errorMessage = 'Unauthorized: Please log in again.';
    } else if (error.status === 422) {
      const errors = error.error?.errors;
      if (errors) {
        const allMsgs: string[] = [];
        Object.values(errors).forEach((v: any) => {
          if (Array.isArray(v)) { allMsgs.push(...v); } else { allMsgs.push(String(v)); }
        });
        errorMessage = allMsgs.join(' ');
      } else {
        errorMessage = error.error?.message || 'Validation failed.';
      }
    } else if (error.status === 500) {
      errorMessage = error.error?.message || 'Server Error. Please try again.';
    } else {
      errorMessage = error.error?.message || `Error ${error.status}`;
    }

    console.error('[AdminAuctionService Error]:', error);
    return throwError(() => new Error(errorMessage));
  }
}
