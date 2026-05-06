import { Injectable, inject } from '@angular/core';
import { HttpClient, HttpErrorResponse, HttpParams } from '@angular/common/http';
import { Observable, throwError } from 'rxjs';
import { catchError, map } from 'rxjs/operators';
import { environment } from '../../../environments/environment';

/**
 * Represents an auction as returned by the user's own auction listing endpoint.
 * Maps directly from the AuctionResource fields returned by Laravel.
 */
export interface UserAuction {
  id: number;
  title: string;
  description: string;
  category: string;
  categoryId?: number;
  startingPrice: number;
  currentBid: number;
  status: 'pending' | 'active' | 'closed' | 'cancelled';
  startTime: string;
  endTime: string;
  imageUrl: string;
  totalBids: number;
  createdAt: string;
}

/**
 * Payload to send when creating a new auction.
 * Matches the StoreAuctionRequest validation rules on the backend.
 */
export interface CreateAuctionPayload {
  title: string;
  description: string;
  category_id: number;
  starting_price: number;
  start_time: string;
  end_time: string;
  min_increment?: number;
  reserve_price?: number;
  location?: string;
  images?: File[];
}

/**
 * UserAuctionService
 *
 * Handles all auction operations performed by a regular logged-in user:
 * - Fetching their own auctions (GET /api/user/auctions)
 * - Creating a new auction submission (POST /api/auctions)
 */
@Injectable({
  providedIn: 'root'
})
export class UserAuctionService {
  private http = inject(HttpClient);
  private baseApiUrl = environment.apiUrl;
  private baseUrl = environment.apiUrl.replace('/api', '');

  /**
   * Fetch auctions created by the currently authenticated user.
   * Endpoint: GET /api/user/auctions
   */
  getMyAuctions(params?: { status?: string; page?: number }): Observable<{
    data: UserAuction[];
    current_page: number;
    last_page: number;
    total: number;
  }> {
    let httpParams = new HttpParams();
    if (params?.status && params.status !== 'all') {
      httpParams = httpParams.set('status', params.status);
    }
    if (params?.page) {
      httpParams = httpParams.set('page', params.page.toString());
    }

    return this.http.get<any>(`${this.baseApiUrl}/user/auctions`, { params: httpParams }).pipe(
      map(response => {
        const rawData = response.data || [];
        return {
          data: Array.isArray(rawData) ? rawData.map((item: any) => this.mapToUserAuction(item)) : [],
          current_page: response.meta?.current_page || 1,
          last_page: response.meta?.last_page || 1,
          total: response.meta?.total || 0
        };
      }),
      catchError(this.handleError)
    );
  }

  /**
   * Submit a new auction for admin approval.
   * Endpoint: POST /api/auctions
   * Backend automatically sets status = 'pending' for non-admin users.
   */
  createAuction(payload: CreateAuctionPayload): Observable<{ message: string; data: UserAuction }> {
    const formData = new FormData();

    formData.append('title', payload.title);
    formData.append('description', payload.description);
    formData.append('category_id', payload.category_id.toString());
    formData.append('starting_price', payload.starting_price.toString());
    formData.append('start_time', payload.start_time);
    formData.append('end_time', payload.end_time);

    if (payload.min_increment) {
      formData.append('min_increment', payload.min_increment.toString());
    }
    if (payload.reserve_price) {
      formData.append('reserve_price', payload.reserve_price.toString());
    }
    if (payload.location) {
      formData.append('location', payload.location);
    }

    // Attach image files — backend requires at least 1 image
    if (payload.images && payload.images.length > 0) {
      payload.images.forEach((file, index) => {
        formData.append(`images[${index}]`, file, file.name);
      });
    }

    return this.http.post<any>(`${this.baseApiUrl}/auctions`, formData).pipe(
      map(response => ({
        message: response.message || 'Auction submitted successfully!',
        data: this.mapToUserAuction(response.data || response)
      })),
      catchError(this.handleError)
    );
  }

  /**
   * Delete a user's own pending auction.
   * Endpoint: DELETE /api/auctions/{id}
   */
  deleteAuction(id: number): Observable<{ message: string }> {
    return this.http.delete<any>(`${this.baseApiUrl}/auctions/${id}`).pipe(
      map(response => ({ message: response.message || 'Auction deleted.' })),
      catchError(this.handleError)
    );
  }

  /**
   * Maps raw Laravel AuctionResource response to the frontend UserAuction model.
   */
  private mapToUserAuction(raw: any): UserAuction {
    const placeholder = 'https://placehold.co/400x300/4e73df/white?text=Auction';
    let imageUrl = placeholder;
    const firstImage = raw.images?.[0]?.url || raw.image || raw.imageUrl;

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
      title: raw.title || 'Untitled Auction',
      description: raw.description || '',
      category: raw.category?.name ?? raw.category ?? 'N/A',
      categoryId: raw.category?.id ?? raw.category_id,
      startingPrice: parseFloat(raw.starting_price ?? raw.startingPrice ?? 0),
      currentBid: parseFloat(raw.current_price ?? raw.currentBid ?? 0),
      status: raw.status ?? 'pending',
      startTime: raw.start_time ?? raw.startTime ?? '',
      endTime: raw.end_time ?? raw.endTime ?? '',
      imageUrl,
      totalBids: raw.bids_count ?? 0,
      createdAt: raw.created_at ?? ''
    };
  }

  private handleError(error: HttpErrorResponse): Observable<never> {
    let errorMessage = 'An unexpected error occurred.';

    if (error.error instanceof ErrorEvent) {
      errorMessage = `Network Error: ${error.error.message}`;
    } else if (error.status === 0) {
      errorMessage = 'Backend is not reachable. Please check your internet connection.';
    } else if (error.status === 401) {
      errorMessage = 'Unauthorized: Please log in to continue.';
    } else if (error.status === 422) {
      const errors = error.error?.errors;
      if (errors) {
        const allMsgs: string[] = [];
        Object.values(errors).forEach((v: any) => {
          if (Array.isArray(v)) { allMsgs.push(...v); } else { allMsgs.push(String(v)); }
        });
        errorMessage = allMsgs.join(' ');
      } else {
        errorMessage = error.error?.message || 'Validation failed. Please check your input.';
      }
    } else if (error.status === 500) {
      errorMessage = error.error?.message || 'Server Error. Please try again later.';
    } else {
      errorMessage = error.error?.message || `Error ${error.status}`;
    }

    console.error('[UserAuctionService Error]:', error);
    return throwError(() => new Error(errorMessage));
  }
}
